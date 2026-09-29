<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Work & Task Management</h2>
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500 font-medium">Start Date:</span>
                <input wire:model.live="fromDate" type="date" class="block w-full border border-gray-300 rounded-md py-1.5 px-3 bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500 font-medium">End Date:</span>
                <input wire:model.live="toDate" type="date" class="block w-full border border-gray-300 rounded-md py-1.5 px-3 bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search assignees..." class="block w-full pl-3 pr-3 py-1.5 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
            </div>
            <button type="button" wire:click.prevent="openCreateModal" style="cursor: pointer; z-index: 50; position: relative;" class="inline-flex items-center px-4 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                Assign Tasks
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md shadow-sm">
            <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
        </div>
    @endif

    <x-table>
        <x-table.thead>
            <x-table.tr>
                <x-table.th>Assignee</x-table.th>
                <x-table.th>Assigned On</x-table.th>
                <x-table.th>Total Tasks</x-table.th>
                <x-table.th class="text-right">Actions</x-table.th>
            </x-table.tr>
        </x-table.thead>
        <x-table.tbody>
            @forelse($groupedTasks as $group)
                <x-table.tr>
                    <x-table.td>
                        <div class="font-medium text-gray-900">{{ optional($group->user)->name }}</div>
                        <div class="text-xs text-gray-500">{{ optional($group->user)->email }}</div>
                    </x-table.td>
                    <x-table.td>
                        <span class="text-gray-700">{{ \Carbon\Carbon::parse($group->assign_date)->format('M d, Y') }}</span>
                    </x-table.td>
                    <x-table.td>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $group->tasks_count }} Tasks
                        </span>
                    </x-table.td>
                    <x-table.td class="text-right space-x-2">
                        <a href="{{ route('tasks.user', ['userId' => $group->user_id, 'date' => $group->assign_date]) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            View Details & Verify
                        </a>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="4" class="text-center text-gray-500 py-8">
                        No tasks found matching your criteria.
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>
    <div class="mt-4">
        {{ $groupedTasks->links() }}
    </div>

    <!-- Modals -->
    <div x-data="{ open: @entangle('isModalOpen') }" x-show="open" x-cloak style="display: none;">
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 sm:flex sm:items-center sm:justify-between rounded-t-lg">
                        <h3 class="text-lg font-bold text-gray-900">{{ $task_id ? 'Edit Task' : 'Bulk Assign Tasks' }}</h3>
                        @if(!$task_id)
                            <div class="mt-3 sm:mt-0 sm:ml-4">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Assign All Tasks To:</label>
                                <select wire:model="user_id" class="mt-1 block w-48 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm font-medium">
                                    <option value="">Select Assignee</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->getRoleNames()->first() }})</option>
                                    @endforeach
                                </select>
                                @error('user_id') <span class="text-red-500 text-xs block font-normal mt-1">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>

                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 overflow-y-auto max-h-[60vh]">
                        @if(count($tasks_form) === 0)
                            <p class="text-gray-500 text-sm">No tasks added yet.</p>
                        @endif

                        <div class="space-y-6">
                            @foreach($tasks_form as $index => $t)
                            <div wire:key="task-row-{{ $index }}" class="bg-gray-50 p-4 rounded-lg border border-gray-200 relative">
                                @if(!$task_id && count($tasks_form) > 1)
                                    <button type="button" wire:click="removeTaskRow({{ $index }})" class="absolute top-4 right-4 text-red-500 hover:text-red-700 text-sm font-medium">
                                        Remove Task
                                    </button>
                                @endif
                                <h4 class="text-md font-semibold text-gray-700 mb-3">Task #{{ $index + 1 }}</h4>
                                
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                    <div class="col-span-1 lg:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Task Title</label>
                                        <input type="text" wire:model="tasks_form.{{ $index }}.title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        @error('tasks_form.'.$index.'.title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-span-1 lg:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Project (Optional)</label>
                                        <select wire:model="tasks_form.{{ $index }}.project_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="">General Task</option>
                                            @foreach($projects as $p)
                                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('tasks_form.'.$index.'.project_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-span-1 lg:col-span-4">
                                        <label class="block text-sm font-medium text-gray-700">Description / Instructions</label>
                                        <textarea wire:model="tasks_form.{{ $index }}.description" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                        @error('tasks_form.'.$index.'.description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div class="col-span-1">
                                        <label class="block text-sm font-medium text-gray-700">Total Deliverables (Target)</label>
                                        <input type="number" min="1" wire:model="tasks_form.{{ $index }}.target_count" placeholder="e.g. 50" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <p class="text-xs text-gray-500 mt-0.5">How many items total?</p>
                                        @error('tasks_form.'.$index.'.target_count') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-span-1">
                                        <label class="block text-sm font-medium text-gray-700">Total Payout Amount ()</label>
                                        <input type="number" step="0.01" wire:model="tasks_form.{{ $index }}.amount" placeholder="e.g. 500.00" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <p class="text-xs text-gray-500 mt-0.5">Paid when 100% complete.</p>
                                        @error('tasks_form.'.$index.'.amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                        <input type="date" wire:model="tasks_form.{{ $index }}.start_date" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all duration-200 hover:border-gray-400 sm:text-sm">
                                        @error('tasks_form.'.$index.'.start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                                        <input type="date" wire:model="tasks_form.{{ $index }}.end_date" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all duration-200 hover:border-gray-400 sm:text-sm">
                                        @error('tasks_form.'.$index.'.end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        @if(!$task_id)
                        <div class="mt-4">
                            <button type="button" wire:click="addTaskRow" class="inline-flex items-center px-4 py-2 border border-dashed border-indigo-400 rounded-md text-sm font-medium text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none w-full justify-center">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Another Task
                            </button>
                        </div>
                        @endif
                    </div>
                    <div class="bg-gray-50 px-4 py-4 border-t border-gray-200 sm:flex sm:flex-row-reverse rounded-b-lg">
                        <button wire:click.throttle.3000ms="store" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-6 py-2.5 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ $task_id ? 'Update Task' : 'Save All Tasks' }}
                        </button>
                        <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
