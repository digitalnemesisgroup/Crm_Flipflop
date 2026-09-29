<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">My Bank Accounts</h2>
        @hasanyrole("SUPER ADMIN|ADMIN")
        <button wire:click="create()" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Bank Account
        </button>
        @endhasanyrole
    </div>

    @if(session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md flex items-center shadow-sm"
             x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)">
            <svg class="h-5 w-5 text-green-400 " fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
        </div>
    @endif

    <!-- Accounts List -->
    @if($accounts->isEmpty())
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <p class="text-gray-500 text-sm">No bank accounts added yet.</p>
            <p class="text-gray-400 text-xs mt-1">Add your bank account to receive payouts.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($accounts as $acc)
                <div class="bg-white shadow-sm border {{ $acc->is_primary ? 'border-indigo-400 ring-2 ring-indigo-200' : 'border-gray-200' }} rounded-xl p-6 relative">
                    @if($acc->is_primary)
                        <span class="absolute top-3 right-3 inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-600/20">
                            ★ Primary
                        </span>
                    @endif

                    <!-- Bank Icon + Name -->
                    <div class="flex items-center mb-4">
                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-lg">
                            {{ substr($acc->bank_name, 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-gray-900">{{ $acc->bank_name }}</p>
                            <p class="text-xs text-gray-500">{{ $acc->branch ?: 'Branch not specified' }}</p>
                        </div>
                    </div>

                    <!-- Details -->
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Account Holder</dt>
                            <dd class="text-gray-900 font-medium">{{ $acc->account_holder_name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Account Number</dt>
                            <dd class="text-gray-900 font-mono tracking-wider">
                                {{ str_repeat('•', strlen($acc->account_number) - 4) . substr($acc->account_number, -4) }}
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">IFSC Code</dt>
                            <dd class="text-gray-900 font-mono">{{ $acc->ifsc_code }}</dd>
                        </div>
                    </dl>

                    @hasanyrole("SUPER ADMIN|ADMIN")
                    <!-- Actions -->
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex space-x-3">
                            <button wire:click="edit({{ $acc->id }})" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors" title="Edit"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                            <button wire:click="delete({{ $acc->id }})" wire:confirm="Remove this bank account?" class="text-red-600 hover:text-red-900 text-sm font-medium">Remove</button>
                        </div>
                        @if(!$acc->is_primary)
                            <button wire:click="setPrimary({{ $acc->id }})" class="text-xs text-gray-500 hover:text-indigo-600 transition-colors">Set as Primary</button>
                        @endif
                    </div>
                    @endhasanyrole
                </div>
            @endforeach
        </div>
    @endif

    <!-- Add/Edit Modal -->
    @if($isModalOpen)
    <div class="fixed z-50 inset-0 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 pt-6 pb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-5">
                        {{ $account_id ? 'Edit Bank Account' : 'Add Bank Account' }}
                    </h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Account Holder Name</label>
                                <input type="text" wire:model="account_holder_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Full name as per bank">
                                @error('account_holder_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Bank Name</label>
                                <input type="text" wire:model="bank_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="e.g. HDFC Bank, SBI">
                                @error('bank_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Account Number</label>
                                <input type="text" wire:model="account_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm font-mono">
                                @error('account_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">IFSC Code</label>
                                <input type="text" wire:model="ifsc_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm font-mono uppercase">
                                @error('ifsc_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Branch (Optional)</label>
                                <input type="text" wire:model="branch" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            <div class="col-span-2 flex items-center">
                                <input id="is_primary" type="checkbox" wire:model="is_primary" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_primary" class="ml-2 text-sm text-gray-700">Set as primary payout account</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button wire:click="closeModal()" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button wire:click.prevent="save()" type="button" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 shadow-sm">
                        {{ $account_id ? 'Update Account' : 'Add Account' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
