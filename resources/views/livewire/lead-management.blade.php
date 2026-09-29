<div class="space-y-6">
    <!-- Header and Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Leads & Work Management</h2>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search leads..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
            </div>
            <button type="button" wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Lead / Work
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

    @if (session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md flex items-center shadow-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Data Table -->
    <x-table>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th>Lead Info</x-table.th>
                        <x-table.th>Client</x-table.th>
                        <x-table.th>Assigned To</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th>Est. Value</x-table.th>
                        <x-table.th class="text-right">Actions</x-table.th>
                    </x-table.tr>
                </x-table.thead>
                <x-table.tbody>
                    @forelse($leads as $lead)
                        <x-table.tr>
                            <x-table.td>
                                <div class="text-sm font-medium text-gray-900">{{ $lead->title }}</div>
                                @if($lead->work_type)
                                    <div class="text-xs text-gray-500">{{ $lead->work_type }}</div>
                                @endif
                            </x-table.td>
                            <x-table.td>
                                <div class="text-sm text-gray-900">{{ $lead->client->name ?? 'No Client' }}</div>
                                @if($lead->client?->company)
                                    <div class="text-xs text-gray-500">{{ $lead->client->company }}</div>
                                @endif
                            </x-table.td>
                            <x-table.td>
                                {{ $lead->assignee->name ?? 'Unassigned' }}
                            </x-table.td>
                            <x-table.td>
                                @php
                                    $statusColors = [
                                        'new' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20',
                                        'contacted' => 'bg-purple-100 text-purple-800',
                                        'follow-up' => 'bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-600/20',
                                        'interested' => 'bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20',
                                        'matured' => 'bg-orange-100 text-orange-800',
                                        'converted' => 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20',
                                        'lost' => 'bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20',
                                    ];
                                    $colorClass = $statusColors[$lead->status] ?? 'bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md ring-1 ring-inset ring-gray-500/10 text-xs font-medium {{ $colorClass }} capitalize">
                                    {{ str_replace('-', ' ', $lead->status) }}
                                </span>
                            </x-table.td>
                            <x-table.td>
                                {{ $lead->estimated_value ? '' . number_format($lead->estimated_value, 2) : '-' }}
                            </x-table.td>
                            <x-table.td>
<div class="flex items-center justify-end space-x-2">
@if($lead->status === 'matured')
                                    <button wire:click="openConvertModal({{ $lead->id }})" class="text-green-600 hover:text-green-900 bg-green-50 px-2 py-1 rounded">Convert to Project</button>
                                @endif
                                <button wire:click="edit({{ $lead->id }})" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors" title="Edit"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                                <button wire:click="delete({{ $lead->id }})" wire:confirm="Are you sure you want to delete this lead?" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Delete"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
</div>
</x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr>
                            <x-table.td colspan="6">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <p class="text-lg font-medium">No leads or work entries found</p>
                                    <p class="text-sm">Get started by creating a new entry.</p>
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table>
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $leads->links() }}
        </div>

    <!-- Modal -->
    @if($isModalOpen)
    <div wire:key="lead-modal-box" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ $lead_id ? 'Edit Lead / Work' : 'Add New Lead / Work' }}
                            </h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <label for="title" class="block text-sm font-medium text-gray-700">Lead / Work Title</label>
                                    <input type="text" wire:model="title" id="title" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="work_type" class="block text-sm font-medium text-gray-700">Work Type</label>
                                    <select wire:model="work_type" id="work_type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">-- Select Type --</option>
                                        <option value="Calling">Calling</option>
                                        <option value="Software Sales">Software Sales</option>
                                        <option value="Loan Process">Loan Process</option>
                                        <option value="Advertising">Advertising</option>
                                        <option value="Other Work">Other Work</option>
                                    </select>
                                    @error('work_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="description" class="block text-sm font-medium text-gray-700">Work Details / Description</label>
                                    <textarea wire:model="description" id="description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                    @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <label for="client_id" class="block text-sm font-medium text-gray-700">Client Information</label>
                                    <select wire:model="client_id" id="client_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">-- No Client (Optional) --</option>
                                        @foreach($clients as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->company ?? 'Individual' }})</option>
                                        @endforeach
                                    </select>
                                    @error('client_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="assigned_to" class="block text-sm font-medium text-gray-700">Assign To Employee</label>
                                    <select wire:model="assigned_to" id="assigned_to" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">-- Unassigned --</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->roles->first()?->name }})</option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Lead Status</label>
                                    <select wire:model="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="new">New</option>
                                        <option value="contacted">Contacted</option>
                                        <option value="follow-up">Follow-up</option>
                                        <option value="interested">Interested</option>
                                        <option value="matured">Matured</option>
                                        <option value="converted">Converted</option>
                                        <option value="lost">Lost</option>
                                    </select>
                                    @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="estimated_value" class="block text-sm font-medium text-gray-700">Estimated Value / Project Value</label>
                                    <input type="number" step="0.01" wire:model="estimated_value" id="estimated_value" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('estimated_value') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click.prevent="store()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Submit
                    </button>
                    <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Convert Modal -->
    @if($isConvertModalOpen)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="convert-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" @click="$wire.closeModal()"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="convert-modal-title">
                                Convert Lead to Project
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Set the standard Employee Payout Percentage for this project. This can be adjusted later if needed.
                                </p>
                            </div>
                            <div class="mt-4">
                                <label for="convert_payout_percentage" class="block text-sm font-medium text-gray-700">Employee Payout %</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" step="0.1" wire:model="convert_payout_percentage" id="convert_payout_percentage" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-8 sm:text-sm border-gray-300 rounded-md">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                                @error('convert_payout_percentage') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click.prevent="processConversion()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirm Conversion
                    </button>
                    <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
