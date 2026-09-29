<div class="space-y-6">
    <!-- Header and Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Projects</h2>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search projects..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
            </div>
            <button type="button" wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Project
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
                        <x-table.th>Project Name</x-table.th>
                        <x-table.th>Client</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th>Team</x-table.th>
                        <x-table.th>Budget</x-table.th>
                        <x-table.th class="text-right">Actions</x-table.th>
                    </x-table.tr>
                </x-table.thead>
                <x-table.tbody>
                    @forelse($projects as $project)
                        <x-table.tr>
                            <x-table.td>
                                <div class="text-sm font-medium text-gray-900">{{ $project->name }}</div>
                                @if($project->start_date || $project->end_date)
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $project->start_date ? $project->start_date->format('M d, Y') : 'TBD' }} - 
                                        {{ $project->end_date ? $project->end_date->format('M d, Y') : 'TBD' }}
                                    </div>
                                @endif
                            </x-table.td>
                            <x-table.td>
                                <div class="text-sm text-gray-900">{{ $project->client->name ?? '-' }}</div>
                            </x-table.td>
                            <x-table.td>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20',
                                        'active' => 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20',
                                        'completed' => 'bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-600/20',
                                        'cancelled' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20',
                                    ];
                                    $colorClass = $statusColors[$project->status] ?? 'bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md ring-1 ring-inset ring-gray-500/10 text-xs font-medium {{ $colorClass }} capitalize">
                                    {{ $project->status }}
                                </span>
                            </x-table.td>
                            <x-table.td>
                                <div class="flex -space-x-2 overflow-hidden">
                                    @forelse($project->users->take(3) as $user)
                                        <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-gray-300 flex items-center justify-center text-xs font-bold text-gray-600" title="{{ $user->name }}">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @empty
                                        <span class="text-xs text-gray-400">None</span>
                                    @endforelse
                                    @if($project->users->count() > 3)
                                        <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-gray-100 flex items-center justify-center text-xs font-medium text-gray-500">
                                            +{{ $project->users->count() - 3 }}
                                        </div>
                                    @endif
                                </div>
                            </x-table.td>
                            <x-table.td>
                                {{ $project->total_budget ? '' . number_format($project->total_budget, 2) : '-' }}
                            </x-table.td>
                            <x-table.td>
<div class="flex items-center justify-end space-x-2">
<a href="{{ route('projects.details', $project->id) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors" title="View"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg></a>
                                <button wire:click="edit({{ $project->id }})" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors" title="Edit"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                                <button wire:click="delete({{ $project->id }})" wire:confirm="Are you sure you want to delete this project?" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Delete"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
</div>
</x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr>
                            <x-table.td colspan="6">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <p class="text-lg font-medium">No projects found</p>
                                    <p class="text-sm">Get started by creating a new project.</p>
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table>
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $projects->links() }}
        </div>

    <!-- Modal -->
    @if($isModalOpen)
    <div wire:key="project-modal-box" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ $project_id ? 'Edit Project' : 'Create New Project' }}
                            </h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Project Name</label>
                                    <input type="text" wire:model="name" id="name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea wire:model="description" id="description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                    @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                                    <select wire:model="client_id" id="client_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">-- Select Client --</option>
                                        @foreach($clients as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->company ?? 'Individual' }})</option>
                                        @endforeach
                                    </select>
                                    @error('client_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="lead_id" class="block text-sm font-medium text-gray-700">Linked Lead (Optional)</label>
                                    <select wire:model="lead_id" id="lead_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">-- No Linked Lead --</option>
                                        @foreach($leads as $l)
                                            <option value="{{ $l->id }}">{{ $l->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('lead_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select wire:model="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="pending">Pending</option>
                                        <option value="active">Active</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                    @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="total_budget" class="block text-sm font-medium text-gray-700">Total Budget ()</label>
                                    <input type="number" step="0.01" wire:model="total_budget" id="total_budget" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('total_budget') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <label for="employee_payout_percentage" class="block text-sm font-medium text-gray-700">Employee Payout (%)</label>
                                    <input type="number" step="0.01" wire:model="employee_payout_percentage" id="employee_payout_percentage" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="e.g. 40">
                                    @error('employee_payout_percentage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                    <input type="date" wire:model="start_date" id="start_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('start_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                    <input type="date" wire:model="end_date" id="end_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('end_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Assign Team Members (Employees & Freelancers)</label>
                                    <div class="border border-gray-300 rounded-md p-3 max-h-40 overflow-y-auto bg-gray-50">
                                        @forelse($users as $user)
                                            <div class="flex items-center mb-2 last:mb-0">
                                                <input wire:model="assigned_users" id="user_{{ $user->id }}" type="checkbox" value="{{ $user->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                <label for="user_{{ $user->id }}" class="ml-3 text-sm text-gray-700">
                                                    {{ $user->name }}
                                                    <span class="text-xs text-gray-500 ml-1">({{ $user->roles->first()?->name ?? 'User' }})</span>
                                                </label>
                                            </div>
                                        @empty
                                            <p class="text-sm text-gray-500">No users available to assign.</p>
                                        @endforelse
                                    </div>
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
</div>
