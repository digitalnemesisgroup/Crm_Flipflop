<div class="space-y-6">
    <!-- Header and Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Payout Requests</h2>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search requests..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
            </div>
            <button wire:click="create()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Payout Request
            </button>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md flex items-center shadow-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
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

    <!-- Data Table -->
    <x-table>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th>Requested By</x-table.th>
                        <x-table.th>Date</x-table.th>
                        <x-table.th>Amount</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th class="text-right">Actions</x-table.th>
                    </x-table.tr>
                </x-table.thead>
                <x-table.tbody>
                    @forelse($requests as $req)
                        <x-table.tr>
                            <x-table.td>
                                <div class="text-sm font-medium text-gray-900">{{ $req->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $req->user->roles->first()?->name ?? 'User' }}</div>
                            </x-table.td>
                            <x-table.td>
                                <div class="text-sm text-gray-900">{{ $req->created_at->format('M d, Y H:i') }}</div>
                            </x-table.td>
                            <x-table.td>
                                <div class="text-sm font-medium text-gray-900">₹{{ number_format($req->amount, 2) }}</div>
                                @if($req->transaction)
                                    <div class="text-xs text-green-600 mt-0.5" title="{{ $req->transaction->payment_method }} - {{ $req->transaction->reference_number }}">
                                        Paid: {{ $req->transaction->transaction_date->format('M d') }}
                                    </div>
                                @endif
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
                            <x-table.td>
                                @if($req->status === 'pending')
                                    <button wire:click="processRequest({{ $req->id }}, 'approve')" class="text-green-600 hover:text-green-900 bg-green-50 px-2 py-1 rounded">Process Payout</button>
                                    <button wire:click="processRequest({{ $req->id }}, 'reject')" class="text-red-600 hover:text-red-900 ml-2">Reject</button>
                                    <button wire:click="processRequest({{ $req->id }}, 'hold')" class="text-yellow-600 hover:text-yellow-900 ml-2">Hold</button>
                                @else
                                    <span class="text-gray-400 italic">Processed</span>
                                @endif
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr>
                            <x-table.td colspan="5">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <p class="text-lg font-medium">No payout requests</p>
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table>
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $requests->links() }}
        </div>

    <!-- Create Request Modal -->
    @if($isModalOpen)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Create Payout Request
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="user_id" class="block text-sm font-medium text-gray-700">Team Member</label>
                                    <select wire:model.live="user_id" id="user_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">-- Select Member --</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                @if($user_id)
                                    <div class="bg-gray-50 p-3 rounded-md border border-gray-200">
                                        <p class="text-sm text-gray-600">Available Balance to Withdraw:</p>
                                        <p class="text-xl font-bold text-gray-900">₹{{ number_format($available_balance, 2) }}</p>
                                    </div>
                                @endif

                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">Request Amount ($)</label>
                                    <input type="number" step="0.01" wire:model="amount" id="amount" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                                    <textarea wire:model="notes" id="notes" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                    @error('notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click.prevent="storeRequest()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Submit Request
                    </button>
                    <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Process Request Modal (Approve/Reject) -->
    @if($isProcessModalOpen)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="process-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="process-modal-title">
                                {{ $action_type === 'approve' ? 'Process & Record Payout Transaction' : ($action_type === 'hold' ? 'Put Payout on Hold' : 'Reject Payout Request') }}
                            </h3>
                            <div class="mt-4 space-y-4">
                                @if($action_type === 'approve')
                                    <div class="bg-blue-50 p-3 rounded-md">
                                        <p class="text-sm text-blue-700">You are recording a real payout transaction. This will deduct from the user's available balance.</p>
                                    </div>
                                    
                                    <div>
                                        <label for="transaction_date" class="block text-sm font-medium text-gray-700">Transaction Date</label>
                                        <input type="date" wire:model="transaction_date" id="transaction_date" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('transaction_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
    
                                    <div>
                                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                                        <input type="text" wire:model="payment_method" id="payment_method" placeholder="e.g. Bank Transfer, PayPal" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('payment_method') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
    
                                    <div>
                                        <label for="reference_number" class="block text-sm font-medium text-gray-700">Reference / Tx ID (Optional)</label>
                                        <input type="text" wire:model="reference_number" id="reference_number" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('reference_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                                
                                <div>
                                    <label for="admin_notes" class="block text-sm font-medium text-gray-700">Admin Notes {{ in_array($action_type, ['reject', 'hold']) ? '(Required)' : '(Optional)' }}</label>
                                    <textarea wire:model="admin_notes" id="admin_notes" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="{{ in_array($action_type, ['reject', 'hold']) ? 'Reason...' : '' }}"></textarea>
                                    @error('admin_notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    @if($action_type === 'approve')
                        <button wire:click.prevent="storeProcess()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Confirm Payment
                        </button>
                    @elseif($action_type === 'hold')
                        <button wire:click.prevent="storeProcess()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Confirm Hold
                        </button>
                    @else
                        <button wire:click.prevent="storeProcess()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Confirm Rejection
                        </button>
                    @endif
                    <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
