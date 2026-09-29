<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Hub-Signature');
        $apiSalt = env('PAYMENT_HUB_API_SALT');

        if (!$signature || !$apiSalt) {
            Log::warning('Webhook rejected: Missing signature or salt.');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Verify HMAC
        $expectedSignature = hash_hmac('sha256', $payload, $apiSalt);

        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('Webhook rejected: Signature mismatch.');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $data = $request->json()->all();

        if (isset($data['client_order_id'])) {
            $order = MarketplaceOrder::with('items.product')->where('client_order_id', $data['client_order_id'])->first();
            
            if ($order && $order->status === 'pending') {
                $status = $data['status'] ?? 'failed';
                $order->update([
                    'status' => $status,
                    'transaction_id' => $data['transaction_id'] ?? null,
                ]);

                // Restore inventory if failed or user_dropped
                if (in_array($status, ['failed', 'user_dropped'])) {
                    foreach ($order->items as $item) {
                        if ($item->product) {
                            $item->product->increment('quantity', $item->quantity);
                        }
                    }
                }

                Log::info("Marketplace Order {$order->client_order_id} updated to {$order->status} via Webhook.");
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function returnUrl(Request $request)
    {
        $clientOrderId = $request->query('client_order_id');
        $status = $request->query('status');
        
        if ($clientOrderId && $status) {
            $order = MarketplaceOrder::with('items.product')->where('client_order_id', $clientOrderId)->first();
            if ($order && $order->status === 'pending') {
                $order->update(['status' => $status]);

                // Restore inventory if failed or user_dropped
                if (in_array($status, ['failed', 'user_dropped'])) {
                    foreach ($order->items as $item) {
                        if ($item->product) {
                            $item->product->increment('quantity', $item->quantity);
                        }
                    }
                }
            }
            
            if ($status === 'success') {
                session()->flash('message', 'Payment successful! Order: ' . $clientOrderId);
            } else {
                session()->flash('error', 'Payment returned with status: ' . $status);
            }
        }

        return redirect()->route('marketplace');
    }
}
