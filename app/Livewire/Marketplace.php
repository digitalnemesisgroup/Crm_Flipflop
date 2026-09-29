<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MarketplaceOrder;
use App\Models\MarketplaceProduct;
use App\Models\MarketplaceOrderItem;
use App\Jobs\ReleasePendingOrderInventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Marketplace extends Component
{
    public $products = [];
    public $cart = [];
    public $isCheckout = false;

        public function mount()
    {
        $this->cleanupExpiredOrders();
        $this->loadProducts();
        $this->loadCart();
    }

    public function cleanupExpiredOrders()
    {
        try {
            $expiredOrders = MarketplaceOrder::with('items.product')
                ->where('status', 'pending')
                ->where('created_at', '<', now()->subMinutes(5))
                ->get();

            foreach ($expiredOrders as $order) {
                $order->update(['status' => 'user_dropped']);
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('quantity', $item->quantity);
                    }
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Marketplace cleanup error: " . $e->getMessage());
        }
    }

    public function checkOrderStatus($orderId)
    {
        $order = MarketplaceOrder::with('items.product')->find($orderId);
        if (!$order || $order->status !== 'pending') {
            return;
        }

        $hubUrl = env('PAYMENT_HUB_URL', 'https://fiinway.in');
        $apiKey = env('PAYMENT_HUB_API_KEY');

        try {
            if ($apiKey) {
                $response = Http::withHeaders([
                    'X-Api-Key' => $apiKey,
                    'Accept'    => 'application/json',
                ])->get($hubUrl . '/api/hub/status/' . $order->client_order_id);

                if ($response->successful()) {
                    $data = $response->json();
                    $newStatus = $data['status'] ?? 'pending';
                    if (in_array($newStatus, ['success', 'failed', 'user_dropped', 'rejected', 'cancelled'])) {
                        $order->update(['status' => $newStatus]);
                        if (in_array($newStatus, ['failed', 'user_dropped', 'rejected', 'cancelled'])) {
                            foreach ($order->items as $item) {
                                if ($item->product) {
                                    $item->product->increment('quantity', $item->quantity);
                                }
                            }
                        }
                        session()->flash('message', 'Order status updated: ' . ucfirst($newStatus));
                        $this->loadProducts();
                        return;
                    }
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Marketplace checkOrderStatus exception: " . $e->getMessage());
        }

        $order->update(['status' => 'user_dropped']);
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('quantity', $item->quantity);
            }
        }
        session()->flash('message', 'Order marked as User Dropped and stock restored.');
        $this->loadProducts();
    }

    public function loadProducts()
    {
        try {
            $this->products = MarketplaceProduct::all();
        } catch (\Exception $e) {
            $this->products = collect();
        }
    }

    public function loadCart()
    {
        $sessionId = session()->getId();
        $this->cart = Cache::get('cart_' . $sessionId, []);
    }

    public function saveCart()
    {
        $sessionId = session()->getId();
        Cache::put('cart_' . $sessionId, $this->cart, now()->addHours(2));
    }

    public function addToCart($productId)
    {
        $product = MarketplaceProduct::find($productId);
        if (!$product || $product->quantity <= 0) {
            session()->flash('error', 'Product is out of stock.');
            return;
        }

        $qtyInCart = $this->cart[$productId]['quantity'] ?? 0;
        if ($qtyInCart + 1 > $product->quantity) {
            session()->flash('error', 'Not enough quantity available.');
            return;
        }

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
        } else {
            $this->cart[$productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

        $this->saveCart();
        session()->flash('message', 'Added to cart!');
    }

    public function removeFromCart($productId)
    {
        if (isset($this->cart[$productId])) {
            unset($this->cart[$productId]);
            $this->saveCart();
        }
    }

    public function proceedToCheckout()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Your cart is empty.');
            return;
        }
        $this->isCheckout = true;
    }

    public function goBackToShop()
    {
        $this->isCheckout = false;
    }

    public function getCartTotal()
    {
        $total = 0;
        foreach ($this->cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function initiatePayment($gateway = 'cashfree')
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Your cart is empty.');
            return;
        }

        $userId = Auth::id();
        $sessionId = session()->getId();
        
        // 1. Re-validate inventory
        foreach ($this->cart as $productId => $item) {
            $product = MarketplaceProduct::find($productId);
            if (!$product || $product->quantity < $item['quantity']) {
                session()->flash('error', "Not enough quantity for {$item['name']}. It may have been purchased by someone else.");
                return;
            }
        }

        // 2. Lock inventory
        foreach ($this->cart as $productId => $item) {
            $product = MarketplaceProduct::find($productId);
            $product->decrement('quantity', $item['quantity']);
        }

        if ($userId) {
            $clientOrderId = 'MP-' . $userId . '-' . time() . '-' . Str::random(4);
        } else {
            $clientOrderId = 'MP-GUEST-' . substr($sessionId, 0, 10) . '-' . time() . '-' . Str::random(4);
        }

        $totalAmount = $this->getCartTotal();

        // 3. Create pending order
        $order = MarketplaceOrder::create([
            'user_id' => $userId,
            'client_order_id' => $clientOrderId,
            'amount' => $totalAmount,
            'status' => 'pending',
        ]);

        // 4. Create Order Items
        foreach ($this->cart as $productId => $item) {
            MarketplaceOrderItem::create([
                'marketplace_order_id' => $order->id,
                'marketplace_product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // 5. Dispatch job to release inventory if unpaid in 15 mins
        // Cleanup is handled lazily on page load

        // 6. Send to Payment Hub
        $hubUrl = env('PAYMENT_HUB_URL', 'https://fiinway.in');
        $apiKey = env('PAYMENT_HUB_API_KEY');
        $apiSalt = env('PAYMENT_HUB_API_SALT');

        if (!$apiKey || !$apiSalt) {
            session()->flash('error', 'Payment Hub API Key or Salt is not configured in .env');
            return;
        }

        $returnUrl = route('marketplace.return');
        
        $canonicalString = $clientOrderId . '|' . $totalAmount . '|' . $returnUrl;
        $signature = hash_hmac('sha256', $canonicalString, $apiSalt);

        $endpoint = $gateway === 'phonepe' ? '/api/hub/phonepe/initiate' : '/api/hub/initiate';

        try {
            $response = Http::withHeaders([
                'X-Api-Key' => $apiKey,
                'Accept' => 'application/json',
            ])->post($hubUrl . $endpoint, [
                'client_order_id' => $clientOrderId,
                'amount' => $totalAmount,
                'customer_phone' => '9999999999',
                'return_url' => $returnUrl,
                'webhook_url' => route('api.hub.webhook'),
                'signature' => $signature,
            ]);

            if ($response->successful()) {
                // Clear cart immediately since we have reserved the inventory
                Cache::forget('cart_' . $sessionId);
                $this->cart = [];

                $data = $response->json();
                return redirect()->away($data['payment_url']);
            } else {
                // If payment creation fails instantly, restore inventory and delete order
                $order->update(['status' => 'failed']);
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('quantity', $item->quantity);
                    }
                }
                session()->flash('error', 'Failed to connect to Payment Hub (' . $gateway . '): ' . $response->body());
            }
        } catch (\Exception $e) {
            // Restore inventory on exception
            $order->update(['status' => 'failed']);
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('quantity', $item->quantity);
                }
            }
            session()->flash('error', 'Error connecting to Payment Hub: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        try {
            $query = MarketplaceOrder::query();
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('client_order_id', 'like', 'MP-GUEST-' . substr($sessionId, 0, 10) . '%');
            }

            $orders = $query->latest()->get();
        } catch (\Exception $e) {
            $orders = collect();
        }

        return view('livewire.marketplace', compact('orders'))
            ->layout('layouts.marketplace'); 
    }
}
