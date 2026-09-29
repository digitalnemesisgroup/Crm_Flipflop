<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">My Work / Leads</h2>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search my work..." class="block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <button type="button" wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                Submit New Work
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md flex items-center shadow-sm">
            <div class="ml-3"><p class="text-sm font-medium text-green-800">{{ session('message') }}</p></div>
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md flex items-center shadow-sm">
            <div class="ml-3"><p class="text-sm font-medium text-red-800">{{ session('error') }}</p></div>
        </div>
    @endif

    <x-table>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th>Work Title</x-table.th>
                        <x-table.th>Client</x-table.th>
                        <x-table.th>Type</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th>Value</x-table.th>
                        <x-table.th class="text-right">Actions</x-table.th>
                    </x-table.tr>
                </x-table.thead>
                <x-table.tbody>
                    @forelse($leads as $lead)
                        <x-table.tr>
                            <x-table.td>{{ $lead->title }}</x-table.td>
                            <x-table.td>{{ $lead->client->name ?? '-' }}</x-table.td>
                            <x-table.td>{{ $lead->work_type ?? '-' }}</x-table.td>
                            <x-table.td>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20 capitalize">
                                    {{ $lead->status }}
                                </span>
                            </x-table.td>
                            <x-table.td>₹{{ number_format($lead->estimated_value, 2) }}</x-table.td>
                            <x-table.td>
<div class="flex items-center justify-end space-x-2">
@if(!in_array($lead->status, ['matured', 'converted']))
                                    <button wire:click="edit({{ $lead->id }})" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors" title="Edit"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                                    <button wire:click="delete({{ $lead->id }})" wire:confirm="Delete this entry?" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Delete"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                @endif
</div>
</x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr>
                            <x-table.td colspan="6">No work entries found.</x-table.td>
                        </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table>
        <div class="px-4 py-3">{{ $leads->links() }}</div>

    @if($isModalOpen)
    <div wire:key="my-leads-modal-box" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $lead_id ? 'Edit Work Entry' : 'Submit New Work' }}</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Work Title</label>
                            <input type="text" wire:model="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Work Type</label>
                            <select wire:model="work_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select Type</option>
                                <option value="Calling">Calling</option>
                                <option value="Software Sales">Software Sales</option>
                                <option value="Loan Process">Loan Process</option>
                                <option value="Advertising">Advertising</option>
                                <option value="Other Work">Other Work</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Client</label>
                            <select wire:model="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">No Client (Optional)</option>
                                @foreach($clients as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estimated Value ()</label>
                            <input type="number" step="0.01" wire:model="estimated_value" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="store()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                    <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
