<div class="space-y-6">
    <!-- Header and Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Clients</h2>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search clients..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
            </div>
            <button wire:click="create()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Client
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
                        <x-table.th>Client Details</x-table.th>
                        <x-table.th>Company & GST</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th class="text-right">Actions</x-table.th>
                    </x-table.tr>
                </x-table.thead>
                <x-table.tbody>
                    @forelse($clients as $client)
                        <x-table.tr>
                            <x-table.td>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 relative">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-teal-100 to-teal-50 flex items-center justify-center text-teal-700 font-bold border border-gray-100 shadow-sm">
                                            {{ substr($client->name, 0, 1) }}
                                        </div>
                                        @php
                                            $dotColors = [
                                                'active' => 'bg-green-500 ring-white',
                                                'inactive' => 'bg-gray-400 ring-white',
                                            ];
                                            $dotClass = $dotColors[$client->status ?? 'active'] ?? 'bg-green-500 ring-white';
                                        @endphp
                                        <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 {{ $dotClass }}"></span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $client->name }}</div>
                                        <div class="text-xs text-gray-500 flex items-center mt-0.5">
                                            <span>{{ $client->email }}</span>
                                            @if($client->phone)
                                                <span class="mx-1.5 text-gray-300">•</span>
                                                <span>{{ $client->phone }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </x-table.td>
                            <x-table.td>
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $client->company ?: 'No Company' }}
                                    </span>
                                    @if($client->gst_number)
                                    <div class="flex items-center mt-1 space-x-2">
                                        <span class="inline-block px-2 py-0.5 text-[10px] font-medium bg-gray-100 text-gray-600 border border-gray-200 rounded-md shadow-sm">GST: {{ $client->gst_number }}</span>
                                    </div>
                                    @endif
                                </div>
                            </x-table.td>
                            <x-table.td>
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-50 text-green-700 ring-green-600/20',
                                        'inactive' => 'bg-gray-50 text-gray-600 ring-gray-500/10',
                                    ];
                                    $badgeClass = $statusColors[$client->status ?? 'active'] ?? 'bg-green-50 text-green-700 ring-green-600/20';
                                @endphp
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $badgeClass }} capitalize">
                                    {{ $client->status }}
                                </span>
                            </x-table.td>
                            <x-table.td>
                                <div class="flex items-center justify-end space-x-2">
                                    <button wire:click="edit({{ $client->id }})" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors" title="Edit Client">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $client->id }})" wire:confirm="Are you sure you want to delete this client?" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Delete Client">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr>
                            <x-table.td colspan="4">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <p class="text-lg font-medium">No clients found</p>
                                    <p class="text-sm">Get started by adding a new client.</p>
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table>
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $clients->links() }}
        </div>

    <!-- Modal -->
    @if($isModalOpen)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" @click="$wire.closeModal()"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 border-b pb-2" id="modal-title">
                                {{ $client_id ? 'Edit Client Details' : 'Add New Client' }}
                            </h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Contact Person Name</label>
                                    <input type="text" wire:model="name" id="name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <label for="company" class="block text-sm font-medium text-gray-700">Company Name</label>
                                    <input type="text" wire:model="company" id="company" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('company') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                                    <input type="email" wire:model="email" id="email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Mobile / Phone</label>
                                    <input type="text" wire:model="phone" id="phone" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <label for="gst_number" class="block text-sm font-medium text-gray-700">GST Number</label>
                                    <input type="text" wire:model="gst_number" id="gst_number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('gst_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select wire:model="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Complete Address</label>
                                    <textarea wire:model="address" id="address" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                    @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                    <button wire:click.prevent="store()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-6 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Client
                    </button>
                    <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
