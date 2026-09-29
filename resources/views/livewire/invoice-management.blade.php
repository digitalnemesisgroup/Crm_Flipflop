<div class="space-y-6">
    <!-- Header and Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Billing & Invoices</h2>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search invoices..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
            </div>
            <button wire:click="create()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Invoice
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
                        <x-table.th>Invoice #</x-table.th>
                        <x-table.th>Project / Client</x-table.th>
                        <x-table.th>Dates</x-table.th>
                        <x-table.th>Amount</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th class="text-right">Actions</x-table.th>
                    </x-table.tr>
                </x-table.thead>
                <x-table.tbody>
                    @forelse($invoices as $invoice)
                        <x-table.tr>
                            <x-table.td>
                                <div class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</div>
                            </x-table.td>
                            <x-table.td>
                                <div class="text-sm text-gray-900 font-medium">{{ $invoice->project->name ?? 'Unknown Project' }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $invoice->client->name ?? 'Unknown Client' }}</div>
                            </x-table.td>
                            <x-table.td>
                                <div class="text-sm text-gray-900">Iss: {{ $invoice->issue_date->format('M d, Y') }}</div>
                                <div class="text-xs {{ $invoice->status !== 'paid' && $invoice->due_date->isPast() ? 'text-red-500 font-bold' : 'text-gray-500' }}">Due: {{ $invoice->due_date->format('M d, Y') }}</div>
                            </x-table.td>
                            <x-table.td>
                                <div class="text-sm font-medium text-gray-900">₹{{ number_format($invoice->amount, 2) }}</div>
                                @if($invoice->amount_paid > 0)
                                    <div class="text-xs text-green-600 mt-0.5">Paid: ₹{{ number_format($invoice->amount_paid, 2) }}</div>
                                @endif
                            </x-table.td>
                            <x-table.td>
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20',
                                        'sent' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20',
                                        'paid' => 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20',
                                        'overdue' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20',
                                        'cancelled' => 'bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20',
                                    ];
                                    $colorClass = $statusColors[$invoice->status] ?? 'bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md ring-1 ring-inset ring-gray-500/10 text-xs font-medium {{ $colorClass }} capitalize">
                                    {{ $invoice->status }}
                                </span>
                            </x-table.td>
                            <x-table.td>
<div class="flex items-center justify-end space-x-2">
<a href="{{ route('invoices.download', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900 inline-flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    PDF
                                </a>
                                @if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
                                    <button wire:click="addPayment({{ $invoice->id }})" class="text-green-600 hover:text-green-900 ml-2">Add Payment</button>
                                @endif
                                <button wire:click="edit({{ $invoice->id }})" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors" title="Edit"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                                <button wire:click="delete({{ $invoice->id }})" wire:confirm="Are you sure you want to delete this invoice?" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Delete"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
</div>
</x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr>
                            <x-table.td colspan="6">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-lg font-medium">No invoices found</p>
                                    <p class="text-sm">Get started by creating a new invoice.</p>
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table>
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $invoices->links() }}
        </div>

    <!-- Invoice Modal -->
    @if($isModalOpen)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ $invoice_id ? 'Edit Invoice' : 'Create New Invoice' }}
                            </h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <label for="invoice_number" class="block text-sm font-medium text-gray-700">Invoice Number</label>
                                    <input type="text" wire:model="invoice_number" id="invoice_number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-50" readonly>
                                    @error('invoice_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="project_id" class="block text-sm font-medium text-gray-700">Project</label>
                                    <select wire:model.live="project_id" id="project_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">-- Select Project --</option>
                                        @foreach($projects as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }} (Total: ₹{{ number_format($p->total_budget, 2) }})</option>
                                        @endforeach
                                    </select>
                                    @error('project_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <label for="milestone_percentage" class="block text-sm font-medium text-gray-700">Milestone Payment</label>
                                    <select wire:model.live="milestone_percentage" id="milestone_percentage" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Custom Amount</option>
                                        <option value="10">10% Payment</option>
                                        <option value="25">25% Payment</option>
                                        <option value="40">40% Payment</option>
                                        <option value="50">50% Payment</option>
                                        <option value="60">60% Payment</option>
                                        <option value="100">100% Full Payment</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Select to auto-calculate the amount below.</p>
                                </div>
                                
                                <div class="sm:col-span-2">
                                    <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                                    <select wire:model="client_id" id="client_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">-- Select Client --</option>
                                        @foreach($clients as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('client_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="issue_date" class="block text-sm font-medium text-gray-700">Issue Date</label>
                                    <input type="date" wire:model="issue_date" id="issue_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('issue_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                                    <input type="date" wire:model="due_date" id="due_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('due_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount ($)</label>
                                    <input type="number" step="0.01" wire:model="amount" id="amount" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select wire:model="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="draft">Draft</option>
                                        <option value="sent">Sent</option>
                                        <option value="paid">Paid</option>
                                        <option value="overdue">Overdue</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                    @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                                    <textarea wire:model="notes" id="notes" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                    @error('notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click.prevent="store()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save
                    </button>
                    <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Payment Modal -->
    @if($isPaymentModalOpen)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="payment-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="payment-modal-title">
                                Record Client Payment
                            </h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-4 gap-x-4">
                                <div class="col-span-1">
                                    <label for="payment_amount" class="block text-sm font-medium text-gray-700">Amount Paid ($)</label>
                                    <input type="number" step="0.01" wire:model="payment_amount" id="payment_amount" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('payment_amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-span-1">
                                    <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date</label>
                                    <input type="date" wire:model="payment_date" id="payment_date" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('payment_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-span-1">
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method (Optional)</label>
                                    <input type="text" wire:model="payment_method" id="payment_method" placeholder="e.g. Stripe, Bank Transfer" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('payment_method') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-span-1">
                                    <label for="reference_number" class="block text-sm font-medium text-gray-700">Reference / Check # (Optional)</label>
                                    <input type="text" wire:model="reference_number" id="reference_number" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('reference_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click.prevent="storePayment()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Record Payment
                    </button>
                    <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
