<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Special Tasks (Forms)</h2>
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
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search tasks..." class="block w-full pl-3 pr-3 py-1.5 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
            </div>
            <button type="button" wire:click.prevent="openAssignModal" style="cursor: pointer; z-index: 50; position: relative;" class="inline-flex items-center px-4 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 whitespace-nowrap">
                Assign Special Task
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
                    <x-table.th>Task & Template</x-table.th>
                    <x-table.th>Assigned Date & Due Date</x-table.th>
                    <x-table.th>Target & Progress</x-table.th>
                    <x-table.th>Status</x-table.th>
                    <x-table.th class="text-right">Actions</x-table.th>
                </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
                @forelse($tasks as $task)
                    <x-table.tr>
                        <x-table.td>{{ $task->user?->name ?? 'Unknown / Deleted User' }}</x-table.td>
                        <x-table.td>
                            <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                            <div class="text-xs text-gray-500">Template: {{ $task->formTemplate?->name ?? 'N/A' }}</div>
                        </x-table.td>
                        <x-table.td>
                            <div class="text-xs font-medium text-gray-900">
                                Assigned: {{ $task->created_at ? $task->created_at->format('M d, Y') : '-' }}
                            </div>
                            <div class="text-xs text-indigo-600 font-medium mt-0.5">
                                Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : ($task->created_at ? $task->created_at->format('M d, Y') : '-') }}
                            </div>
                        </x-table.td>
                        <x-table.td>
                            <div class="text-sm text-gray-900">{{ $task->approvedCount() }} / {{ $task->target_count }} Approved</div>
                            <div class="text-xs text-blue-600">{{ $task->pendingCount() }} Pending Review</div>
                        </x-table.td>
                        <x-table.td>
                            @if($task->status === 'assigned')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20">Assigned</span>
                            @elseif($task->status === 'submitted')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20">Has Submissions</span>
                            @elseif($task->status === 'approved')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20">Complete</span>
                            @endif
                        </x-table.td>
                        <x-table.td>
<div class="flex items-center justify-end space-x-2">
<button wire:click="openVerifyModal({{ $task->id }})" class="text-indigo-600 hover:text-indigo-900 font-semibold">Review Data</button>
                            <button wire:click="delete({{ $task->id }})" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()" title="Delete"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
</div>
</x-table.td>
                    </x-table.tr>
                @empty
                    <x-table.tr>
                        <x-table.td colspan="6">No special tasks assigned yet.</x-table.td>
                    </x-table.tr>
                @endforelse
            </x-table.tbody>
        </x-table>
        <div class="px-4 py-3 border-t border-gray-200">{{ $tasks->links() }}</div>

    <!-- Assign Modal -->
    <div x-data="{ open: @entangle('isAssignModalOpen') }" x-show="open" x-cloak style="display: none;">
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('isAssignModalOpen', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                <form wire:submit.prevent="store">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Assign Special Task</h3>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Assign To *</label>
                            <select wire:model="user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Employee</option>
                                @foreach($users as $u) <option value="{{ $u->id }}">{{ $u->name }}</option> @endforeach
                            </select>
                            @error('user_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($tasks_form as $index => $taskData)
                                <div class="border border-gray-200 rounded-md p-4 bg-gray-50">
                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Task #{{ $index + 1 }}</h4>
                                        @if(count($tasks_form) > 1)
                                            <button type="button" wire:click="removeTaskRow({{ $index }})" class="text-red-500 hover:text-red-700 text-sm font-medium bg-red-50 px-2 py-1 rounded">Remove</button>
                                        @endif
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Form Template *</label>
                                            <select wire:model="tasks_form.{{ $index }}.form_template_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">Select Template</option>
                                                @foreach($templates as $t) <option value="{{ $t->id }}">{{ $t->name }}</option> @endforeach
                                            </select>
                                            @error('tasks_form.'.$index.'.form_template_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Task Title *</label>
                                            <input type="text" wire:model="tasks_form.{{ $index }}.title" placeholder="e.g. Daily Check-in" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('tasks_form.'.$index.'.title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Target Count *</label>
                                                <input type="number" wire:model="tasks_form.{{ $index }}.target_count" min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                @error('tasks_form.'.$index.'.target_count') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Payout (₹)</label>
                                                <input type="number" wire:model="tasks_form.{{ $index }}.amount" min="0" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            </div>
                                                                                        <div>
                                                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                                <input type="date" wire:model="tasks_form.{{ $index }}.start_date" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all duration-200 hover:border-gray-400 sm:text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">End Date</label>
                                                <input type="date" wire:model="tasks_form.{{ $index }}.end_date" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all duration-200 hover:border-gray-400 sm:text-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <button type="button" wire:click="addTaskRow" class="mt-4 inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                            + Add Another Task
                        </button>
                        @error('tasks_form') <span class="text-red-500 text-xs block mt-2">{{ $message }}</span> @enderror
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse border-t border-gray-200">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                        <button type="button" wire:click="$set('isAssignModalOpen', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <!-- Verify Modal -->
    @if($isVerifyModalOpen && $activeTask)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('isVerifyModalOpen', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full">
                <div class="bg-gray-50 px-4 py-4 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Review Form Submissions</h3>
                    <button wire:click="$set('isVerifyModalOpen', false)" class="text-gray-400 hover:text-gray-500">&times;</button>
                </div>
                <div class="bg-white p-6 overflow-y-auto max-h-[70vh]">
                    <div class="mb-6 flex justify-between items-end border-b pb-4">
                        <div>
                            <h4 class="text-lg font-bold text-gray-800">{{ $activeTask->title }}</h4>
                            <p class="text-sm text-gray-500">Assigned to {{ $activeTask->user?->name ?? 'Unknown / Deleted User' }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-xl font-bold text-indigo-600">{{ $activeTask->approvedCount() }} / {{ $activeTask->target_count }}</div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Approved</div>
                        </div>
                    </div>
                    
                    @if(count($activeSubmissions) == 0)
                        <p class="text-center text-gray-500 py-10">No form submissions received yet.</p>
                    @else
                        <div class="space-y-6">
                            @foreach($activeSubmissions as $index => $sub)
                                <div class="border {{ $sub->status == 'approved' ? 'border-green-300 bg-green-50' : ($sub->status == 'rejected' ? 'border-red-300 bg-red-50' : 'border-gray-200 bg-white') }} rounded-md shadow-sm overflow-hidden">
                                    <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center {{ $sub->status == 'approved' ? 'bg-green-100' : ($sub->status == 'rejected' ? 'bg-red-100' : 'bg-gray-50') }}">
                                        <span class="font-bold text-sm text-gray-700">Submission #{{ $index + 1 }} <span class="font-normal text-gray-500 ml-2">({{ $sub->created_at->format('M d, g:i A') }})</span></span>
                                        <div>
                                            @if($sub->status == 'pending')
                                                <button wire:click="updateSubmissionStatus({{ $sub->id }}, 'approved')" class="px-2 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 ">Approve</button>
                                                <button wire:click="updateSubmissionStatus({{ $sub->id }}, 'rejected')" class="px-2 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700">Reject</button>
                                            @else
                                                <span class="text-xs font-bold uppercase {{ $sub->status == 'approved' ? 'text-green-800' : 'text-red-800' }} mr-4">{{ $sub->status }}</span>
                                                <button wire:click="updateSubmissionStatus({{ $sub->id }}, 'pending')" class="text-xs text-gray-600 hover:underline">Undo</button>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4">
                                            @foreach($activeTask->formTemplate?->schema ?? [] as $field)
                                                <div class="sm:col-span-1">
                                                    <dt class="text-xs font-medium text-gray-500 uppercase">{{ $field['label'] }}</dt>
                                                    <dd class="mt-1 text-sm text-gray-900 font-medium break-words">
                                                        @if(is_array($sub->data[$field['id']] ?? ''))
                                                            {{ implode(', ', $sub->data[$field['id']] ?? []) }}
                                                        @else
                                                            {{ $sub->data[$field['id']] ?? '-' }}
                                                        @endif
                                                    </dd>
                                                </div>
                                            @endforeach
                                        </dl>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

