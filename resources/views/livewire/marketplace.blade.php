<div>
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-600 tracking-tight">Marketplace</h2>
            <p class="text-sm text-gray-500 mt-1">Purchase premium add-ons for your CRM.</p>
        </div>
        @if(!$isCheckout)
            <button wire:click="proceedToCheckout" class="relative bg-gray-900 hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-xl shadow-md transition-all duration-200">
                🛒 Cart ({{ collect($cart)->sum('quantity') }})
                @if(collect($cart)->sum('quantity') > 0)
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                        {{ collect($cart)->sum('quantity') }}
                    </span>
                @endif
            </button>
        @endif
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if($isCheckout)
        <!-- Checkout Page -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Checkout</h3>
                <button wire:click="goBackToShop" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                    ← Back to Shop
                </button>
            </div>

            @if(empty($cart))
                <div class="text-center py-12 text-gray-500">
                    Your cart is empty.
                </div>
            @else
                <div class="space-y-4 mb-8">
                    @foreach($cart as $id => $item)
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $item['name'] }}</h4>
                                <p class="text-sm text-gray-500">Qty: {{ $item['quantity'] }} × ₹{{ number_format($item['price'], 2) }}</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="font-bold text-gray-900">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                <button wire:click="removeFromCart({{ $id }})" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                    <div class="flex justify-between items-center py-4 text-xl font-extrabold text-gray-900">
                        <span>Total:</span>
                        <span>₹{{ number_format($this->getCartTotal(), 2) }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <button wire:click="initiatePayment('cashfree')" wire:loading.attr="disabled" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium py-3 px-4 rounded-xl shadow-md transition-all">
                        <span wire:loading.remove wire:target="initiatePayment('cashfree')">Pay ₹{{ number_format($this->getCartTotal(), 2) }} with Cashfree</span>
                        <span wire:loading wire:target="initiatePayment('cashfree')">Processing...</span>
                    </button>
                    <button wire:click="initiatePayment('phonepe')" wire:loading.attr="disabled" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-medium py-3 px-4 rounded-xl shadow-md transition-all">
                        <span wire:loading.remove wire:target="initiatePayment('phonepe')">Pay ₹{{ number_format($this->getCartTotal(), 2) }} with PhonePe</span>
                        <span wire:loading wire:target="initiatePayment('phonepe')">Processing...</span>
                    </button>
                </div>
            @endif
        </div>
    @else
        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @foreach($products as $product)
                <div class="bg-white overflow-hidden shadow-lg shadow-gray-200/50 hover:shadow-xl transition-all duration-300 rounded-2xl border border-gray-100 p-6 flex flex-col items-center text-center transform hover:-translate-y-1 relative">
                    
                    @if($product->quantity <= 0)
                        <div class="absolute inset-0 bg-white/70 flex items-center justify-center z-10 rounded-2xl backdrop-blur-sm">
                            <span class="bg-red-500 text-white px-4 py-1 rounded-full font-bold shadow">Sold Out</span>
                        </div>
                    @endif

                    @if($product->image_url)
                        <div class="w-full h-40 mb-4 rounded-xl overflow-hidden shadow-sm">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-500">
                        </div>
                    @else
                        <div class="h-16 w-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                    @endif
                    <h3 class="text-lg font-bold text-gray-800">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-500 mb-2 mt-2 h-10">{{ Str::limit($product->description, 50) }}</p>
                    <p class="text-xs text-indigo-600 font-semibold mb-4">{{ $product->quantity }} in stock</p>
                    <p class="text-2xl font-extrabold text-gray-900 mb-6">₹{{ number_format($product->price, 2) }}</p>
                    
                    <button wire:click="addToCart({{ $product->id }})" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-xl shadow-md transition-all duration-200 hover:-translate-y-0.5">
                        Add to Cart
                    </button>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Order History -->
    <div class="bg-white overflow-hidden shadow-lg shadow-gray-200/50 sm:rounded-xl border border-gray-100 transition-all duration-300">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">Your Orders</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->client_order_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₹{{ number_format($order->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($order->status == 'success')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Success</span>
                                @elseif($order->status == 'failed' || $order->status == 'user_dropped')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ ucfirst($order->status) }}</span>
                                @else
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        <button wire:click="checkOrderStatus({{ $order->id }})" wire:loading.attr="disabled" class="text-xs text-indigo-600 hover:text-indigo-800 font-bold underline">
                                            Refresh Status
                                        </button>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->transaction_id ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                                No test orders found. Purchase a product to test the integration.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
