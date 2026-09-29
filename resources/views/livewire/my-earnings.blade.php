<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">My Earnings & Payouts</h2>
        <div>
            <button wire:click="requestPayout()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Request Payout
            </button>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md flex items-center shadow-sm">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <dt class="text-sm font-medium text-gray-500 truncate">Total Earning</dt>
            <dd class="mt-1 text-2xl font-semibold text-gray-900">₹{{ number_format($totalEarning, 2) }}</dd>
            <p class="text-xs text-gray-400 mt-1">Approved & cleared</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <dt class="text-sm font-medium text-gray-500 truncate">Paid</dt>
            <dd class="mt-1 text-2xl font-semibold text-green-600">₹{{ number_format($paid, 2) }}</dd>
            <p class="text-xs text-gray-400 mt-1">Actually received</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <dt class="text-sm font-medium text-gray-500 truncate">Due</dt>
            <dd class="mt-1 text-2xl font-semibold text-gray-900">₹{{ number_format($due, 2) }}</dd>
            <p class="text-xs text-gray-400 mt-1">Total pending to pay</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 bg-yellow-50">
            <dt class="text-sm font-medium text-yellow-800 truncate">On Hold (Requested)</dt>
            <dd class="mt-1 text-2xl font-semibold text-yellow-600">₹{{ number_format($requested, 2) }}</dd>
            <p class="text-xs text-yellow-600 mt-1">Pending admin approval</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-green-200 p-6 bg-green-50 relative overflow-hidden">
            <dt class="text-sm font-bold text-green-800 truncate relative z-10">Available Balance</dt>
            <dd class="mt-1 text-3xl font-black text-green-700 relative z-10">₹{{ number_format($available, 2) }}</dd>
            <p class="text-xs text-green-700 mt-1 relative z-10">Ready to withdraw</p>
            <svg class="absolute -bottom-4 -right-4 h-24 w-24 text-green-200 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Payout Requests History -->
        <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Payout History</h3>
            </div>
            <x-table>
                    <x-table.thead>
                        <x-table.tr>
                            <x-table.th>Date</x-table.th>
                            <x-table.th>Amount</x-table.th>
                            <x-table.th>Status</x-table.th>
                        </x-table.tr>
                    </x-table.thead>
                    <x-table.tbody>
                        @forelse($payoutRequests as $req)
                            <x-table.tr>
                                <x-table.td>
                                    {{ $req->created_at->format('M d, Y') }}
                                </x-table.td>
                                <x-table.td>
                                    ₹{{ number_format($req->amount, 2) }}
                                </x-table.td>
                                <x-table.td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20',
                                            'approved' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20',
                                            'paid' => 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20',
                                            'rejected' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20',
                                        ];
                                        $colorClass = $statusColors[$req->status] ?? 'bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md ring-1 ring-inset ring-gray-500/10 text-xs font-medium {{ $colorClass }} capitalize">
                                        {{ $req->status }}
                                    </span>
                                </x-table.td>
                            </x-table.tr>
                        @empty
                            <x-table.tr>
                                <x-table.td colspan="3">
                                    No payout requests yet.
                                </x-table.td>
                            </x-table.tr>
                        @endforelse
                    </x-table.tbody>
                </x-table>

        <!-- Earnings Statement -->
        <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Earnings Statement</h3>
            </div>
            <x-table>
                    <x-table.thead>
                        <x-table.tr>
                            <x-table.th>Date</x-table.th>
                            <x-table.th>Description</x-table.th>
                            <x-table.th>Amount</x-table.th>
                        </x-table.tr>
                    </x-table.thead>
                    <x-table.tbody>
                        @forelse($earningsHistory as $earning)
                            <x-table.tr>
                                <x-table.td>
                                    {{ $earning->date->format('M d, Y') }}
                                </x-table.td>
                                <x-table.td>
                                    {{ $earning->description }}
                                    @if($earning->project)
                                        <div class="text-xs text-indigo-600 mt-1">{{ $earning->project->name }}</div>
                                    @endif
                                </x-table.td>
                                <x-table.td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium {{ $earning->status === 'cleared' || $earning->status === 'paid' ? 'text-green-600' : 'text-gray-400' }}">
                                    +₹{{ number_format($earning->amount, 2) }}
                                    <div class="text-[10px] uppercase tracking-wider {{ $earning->status === 'cleared' || $earning->status === 'paid' ? 'text-green-500' : 'text-gray-400' }}">{{ $earning->status }}</div>
                                </x-table.td>
                            </x-table.tr>
                        @empty
                            <x-table.tr>
                                <x-table.td colspan="3">
                                    No earnings recorded yet.
                                </x-table.td>
                            </x-table.tr>
                        @endforelse
                    </x-table.tbody>
                </x-table>

    <!-- Payout Request Modal -->
    @if($isModalOpen)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" @click="$wire.closeModal()"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 border-b pb-2" id="modal-title">
                                Request Payout
                            </h3>
                            <div class="mt-4 bg-green-50 border border-green-200 rounded-md p-4 mb-4">
                                <p class="text-sm text-green-800">Your available balance is: <strong>₹{{ number_format($available, 2) }}</strong></p>
                            </div>
                            
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="request_amount" class="block text-sm font-medium text-gray-700">Amount to Withdraw ()</label>
                                    <input type="number" step="0.01" wire:model="request_amount" id="request_amount" max="{{ $available }}" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('request_amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes to Admin (Optional)</label>
                                    <textarea wire:model="notes" id="notes" rows="2" placeholder="e.g. Please transfer to HDFC account" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                    @error('notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                    <button wire:click.prevent="submitRequest()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-6 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirm Request
                    </button>
                    <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>