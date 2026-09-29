<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
            {{ $user->name }}'s Tasks 
            (<span class="text-indigo-600">
                @if($fromDate && $toDate && $fromDate === $toDate)
                    {{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }}
                @elseif($fromDate && $toDate)
                    {{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}
                @elseif($fromDate)
                    From {{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }}
                @elseif($toDate)
                    Until {{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}
                @else
                    All Time
                @endif
            </span>)
        </h2>
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
            <a href="{{ route('tasks.manage') }}" class="inline-flex items-center px-4 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Back to All Users
            </a>
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
                    <x-table.th>Task Info</x-table.th>
                    <x-table.th>Progress / Value</x-table.th>
                    <x-table.th>Status</x-table.th>
                    <x-table.th class="text-right">Actions</x-table.th>
                </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
                @forelse($tasks as $task)
                    <x-table.tr>
                        <x-table.td>
                            <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                            <div class="text-xs text-gray-500">{{ $task->project ? 'Project: '.$task->project->name : 'General Task' }}</div>
                            <div class="text-xs text-gray-500 mt-1">Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}</div>
                        </x-table.td>
                        <x-table.td>
                            @php
                                $target = $task->target_count ?: 1;
                                $approvedPct = min(100, ($task->approvedCount() / $target) * 100);
                                $pendingPct = min(100 - $approvedPct, ($task->pendingCount() / $target) * 100);
                            @endphp
                            <div class="flex flex-col mb-1 w-32">
                                <div class="flex items-center space-x-2 mb-1">
                                    <div class="w-24 bg-gray-200 rounded-full h-1.5 flex overflow-hidden">
                                        <div class="bg-green-500 h-1.5" style="width: {{ $approvedPct }}%"></div>
                                        <div class="bg-blue-500 h-1.5" style="width: {{ $pendingPct }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600">{{ $task->approvedCount() + $task->pendingCount() }}/{{ $task->target_count }}</span>
                                </div>
                                <span class="text-[10px] text-gray-500">{{ $task->approvedCount() }} Verified, {{ $task->pendingCount() }} Pending</span>
                            </div>
                            <div class="text-sm font-bold text-green-600">₹{{ $task->amount ? number_format($task->amount, 2) : '0.00' }}</div>
                        </x-table.td>
                        <x-table.td>
                            @php
                                $colors = [
                                    'assigned' => 'bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20',
                                    'submitted' => 'bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20',
                                    'approved' => 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20',
                                    'rejected' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20',
                                ];
                                $status = $task->isComplete() ? 'approved' : $task->status;
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-md ring-1 ring-inset ring-gray-500/10 text-xs font-medium {{ $colors[$status] ?? 'bg-gray-100' }} capitalize">
                                {{ $task->isComplete() ? 'Completed' : $status }}
                            </span>
                        </x-table.td>
                        <x-table.td>
                            <div class="flex flex-col items-end space-y-1">
                                @foreach($task->submissions as $sub)
                                    @if($sub->status === 'pending')
                                        <button wire:click="openVerifyModal({{ $task->id }}, {{ $sub->id }})" class="block text-center text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-2 py-1 rounded text-xs">
                                            Verify Submission ({{ $sub->created_at->format('M d') }})
                                        </button>
                                    @else
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs {{ $sub->status === 'approved' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ ucfirst($sub->status) }}
                                            </span>
                                            <button wire:click.throttle.1000ms="undoSubmission({{ $sub->id }})" class="text-gray-500 hover:text-gray-800 text-xs underline">
                                                Undo
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="flex justify-end space-x-2 mt-3 pt-2 border-t border-gray-100">
                                <button wire:click.throttle.1000ms="edit({{ $task->id }})" class="text-gray-600 hover:text-gray-900 text-xs">Edit Task</button>
                                <button wire:click.throttle.3000ms="delete({{ $task->id }})" class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                            </div>
                        </x-table.td>
                    </x-table.tr>
                @empty
                    <x-table.tr>
                        <x-table.td colspan="4">No tasks found for this user.</x-table.td>
                    </x-table.tr>
                @endforelse
            </x-table.tbody>
        </x-table>
        <div class="px-4 py-3 border-t border-gray-200">{{ $tasks->links() }}</div>
    </div>

    <!-- Edit Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 sm:flex sm:items-center sm:justify-between rounded-t-lg">
                    <h3 class="text-lg font-bold text-gray-900">Edit Task</h3>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 overflow-y-auto max-h-[60vh]">
                    <div class="space-y-6">
                        @if(isset($tasks_form[0]))
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="col-span-1 sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Task Title</label>
                                    <input type="text" wire:model="tasks_form.0.title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div class="col-span-1 sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Project (Optional)</label>
                                    <select wire:model="tasks_form.0.project_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">General Task</option>
                                        @foreach($projects as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-1 sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Description / Instructions</label>
                                    <textarea wire:model="tasks_form.0.description" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Target Count</label>
                                    <input type="number" min="1" wire:model="tasks_form.0.target_count" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Amount</label>
                                    <input type="number" step="0.01" wire:model="tasks_form.0.amount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div class="col-span-1 sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Due Date</label>
                                    <input type="date" wire:model="tasks_form.0.due_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 border-t border-gray-200 sm:flex sm:flex-row-reverse rounded-b-lg">
                    <button wire:click.throttle.3000ms="store" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-6 py-2.5 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">
                        Update Task
                    </button>
                    <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Verify Task Modal -->
    @if($isVerifyModalOpen && $verify_task)
        @php
            $sub = $verify_task->submissions->where('id', $active_submission_id)->first();
        @endphp
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="verify-modal" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-start mb-4 border-b pb-2">
                        <h3 class="text-lg font-medium text-gray-900">Review Deliverable: {{ $verify_task->title }}</h3>
                        <span class="text-sm font-bold text-indigo-600">Progress: {{ $verify_task->approvedCount() }} / {{ $verify_task->target_count }}</span>
                    </div>
                    
                    @if($sub)
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-700">Submission Notes:</h4>
                            <div class="bg-gray-50 p-3 rounded-md mt-1 text-sm text-gray-800 whitespace-pre-line">
                                {{ $sub->notes ?: 'No notes provided.' }}
                            </div>
                            <div class="text-xs text-gray-400 mt-1">Submitted on: {{ $sub->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        
                        @if(is_array($sub->links) && count($sub->links) > 0)
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-700">Attached Links:</h4>
                            <ul class="mt-2 space-y-2">
                                @foreach($sub->links as $link)
                                    <li class="flex items-center bg-blue-50 px-3 py-2 rounded-md border border-blue-100">
                                        <span class="text-xs font-bold text-blue-800 w-24 truncate ">{{ $link['label'] ?? 'Link' }}:</span>
                                        <a href="{{ $link['url'] ?? $link }}" target="_blank" class="text-indigo-600 hover:underline text-sm truncate flex-1">{{ $link['url'] ?? $link }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @else
                            <p class="text-sm text-red-500 italic mb-6">No links attached.</p>
                        @endif
                    @endif
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Admin Feedback (Optional)</label>
                            <textarea wire:model="admin_feedback" rows="2" placeholder="Feedback on this specific deliverable..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Decision</label>
                            <div class="mt-2 flex items-center space-x-6">
                                <label class="inline-flex items-center">
                                    <input type="radio" wire:model="verify_action" value="approve" class="form-radio h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                                    <span class="ml-2 text-sm text-gray-700 font-medium">Approve (+1 Progress)</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" wire:model="verify_action" value="reject" class="form-radio h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500">
                                    <span class="ml-2 text-sm text-gray-700 font-medium">Reject (Needs fix)</span>
                                </label>
                            </div>
                            @error('verify_action') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse border-t border-gray-200">
                    <button wire:click.throttle.3000ms="processVerification" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto">Confirm Decision</button>
                    <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

