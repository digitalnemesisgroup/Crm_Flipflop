<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">My Special Tasks</h2>
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
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md shadow-sm">
            <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tasks as $task)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 flex flex-col">
                <div class="p-5 flex-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 truncate">{{ $task->title }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Form: {{ $task->formTemplate->name }}</p>
                        </div>
                        @if($task->status === 'assigned')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20">Assigned</span>
                        @elseif($task->status === 'submitted')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20">In Progress</span>
                        @elseif($task->status === 'approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20">Complete</span>
                        @elseif($task->status === 'rejected' || $task->submissions->where('status', 'rejected')->count() > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20">Rejected / Resubmit</span>
                        @endif
                    </div>
                    
                    <p class="mt-3 text-sm text-gray-600 line-clamp-2">{{ $task->description }}</p>

                    @if(($task->status === 'rejected' || $task->submissions->where('status', 'rejected')->count() > 0))
                        @php
                            $rejectedSub = $task->submissions->where('status', 'rejected')->last();
                        @endphp
                        <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-md">
                            <h4 class="text-xs font-bold text-red-800 uppercase tracking-wider mb-1">Admin Feedback (Rejected)</h4>
                            <p class="text-xs text-red-700">{{ $rejectedSub->admin_feedback ?? 'Your previous submission was rejected. Please re-fill and submit again.' }}</p>
                        </div>
                    @endif
                    
                    <div class="mt-4">
                        @php
                            $approvedPct = min(100, ($task->approvedCount() / max(1, $task->target_count)) * 100);
                            $pendingPct = min(100 - $approvedPct, ($task->pendingCount() / max(1, $task->target_count)) * 100);
                        @endphp
                        <div class="flex justify-between text-xs font-medium text-gray-500 mb-1">
                            <span>Progress</span>
                            <span>{{ $task->approvedCount() + $task->pendingCount() }} / {{ $task->target_count }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 flex overflow-hidden">
                            <div class="bg-green-500 h-2" style="width: {{ $approvedPct }}%"></div>
                            <div class="bg-blue-500 h-2" style="width: {{ $pendingPct }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs mt-1">
                            <span class="text-green-600">{{ $task->approvedCount() }} Verified</span>
                            @if($task->pendingCount() > 0)
                                <span class="text-blue-600">{{ $task->pendingCount() }} Pending</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-5 py-4 border-t border-gray-200 flex items-center justify-between">
                    <div>
                        @if($task->amount)
                            <span class="text-sm font-bold text-green-600 block">Earn: ₹{{ number_format($task->amount, 2) }}</span>
                        @endif
                        <div class="text-xs text-gray-500 mt-0.5">Assigned: {{ $task->created_at ? $task->created_at->format('M d, Y') : '-' }}</div>
                        <div class="text-xs text-indigo-600 font-medium">Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : ($task->created_at ? $task->created_at->format('M d, Y') : '-') }}</div>
                    </div>
                    
                    @if(!$task->isComplete() && ($task->approvedCount() + $task->pendingCount()) < $task->target_count)
                        <button wire:click="openSubmitModal({{ $task->id }})" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                            Fill Form
                            {{ ($task->status === 'rejected' || $task->submissions->where('status', 'rejected')->count() > 0) ? 'Re-upload / Resubmit' : 'Fill Form' }}
                        </button>
                    @elseif(!$task->isComplete())
                        <span class="text-xs text-gray-500 italic">Pending Admin Review</span>
                        <button wire:click="openSubmitModal({{ $task->id }})" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700">
                            Re-upload / Resubmit
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-500 bg-white shadow-sm rounded-lg border border-gray-200">
                You have no special tasks assigned.
            </div>
        @endforelse
    </div>
    
    <div class="mt-4">{{ $tasks->links() }}</div>

    <!-- Submit Modal -->
    @if($isSubmitModalOpen && $activeTask)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('isSubmitModalOpen', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                <form wire:submit.prevent="submitForm">
                    <div class="bg-indigo-600 px-4 py-4 sm:px-6">
                        <h3 class="text-lg font-medium text-white">{{ $activeTask->title }} - Form Entry</h3>
                        <p class="text-indigo-200 text-sm mt-1">Please fill out this form carefully. Fields marked with * are required.</p>
                    </div>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 space-y-5">
                        
                        @foreach($activeTask->formTemplate->schema as $field)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ $field['label'] }} @if($field['required']) <span class="text-red-500">*</span> @endif
                                </label>
                                
                                @if($field['type'] == 'text')
                                    <input type="text" wire:model="formData.{{ $field['id'] }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                
                                @elseif($field['type'] == 'textarea')
                                    <textarea wire:model="formData.{{ $field['id'] }}" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                
                                @elseif($field['type'] == 'number')
                                    <input type="number" wire:model="formData.{{ $field['id'] }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                
                                @elseif($field['type'] == 'date')
                                    <input type="date" wire:model="formData.{{ $field['id'] }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                
                                @elseif($field['type'] == 'time')
                                    <input type="time" wire:model="formData.{{ $field['id'] }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                
                                @elseif($field['type'] == 'dropdown')
                                    <select wire:model="formData.{{ $field['id'] }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Select an option</option>
                                        @foreach(explode(',', $field['options']) as $option)
                                            <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                                        @endforeach
                                    </select>
                                
                                @elseif($field['type'] == 'radio')
                                    <div class="space-y-2 mt-2">
                                        @foreach(explode(',', $field['options']) as $option)
                                            <div class="flex items-center">
                                                <input type="radio" wire:model="formData.{{ $field['id'] }}" value="{{ trim($option) }}" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                <label class="ml-3 block text-sm font-medium text-gray-700">{{ trim($option) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                @elseif($field['type'] == 'checkbox')
                                    <div class="space-y-2 mt-2">
                                        @foreach(explode(',', $field['options']) as $option)
                                            <div class="flex items-center">
                                                <input type="checkbox" wire:model="formData.{{ $field['id'] }}" value="{{ trim($option) }}" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                                <label class="ml-3 block text-sm font-medium text-gray-700">{{ trim($option) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                
                                @error('formData.'.$field['id']) <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                            </div>
                        @endforeach
                        
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse border-t border-gray-200">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Submit Form</button>
                        <button type="button" wire:click="$set('isSubmitModalOpen', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
