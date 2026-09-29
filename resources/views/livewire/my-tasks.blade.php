<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">My Tasks</h2>
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
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search my tasks..." class="block w-full pl-3 pr-3 py-1.5 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md shadow-sm">
            <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md shadow-sm">
            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tasks as $task)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 flex flex-col">
                <div class="p-5 flex-1">
                    <div class="flex justify-between items-start">
                        <h3 class="text-lg font-medium text-gray-900 truncate" title="{{ $task->title }}">{{ $task->title }}</h3>
                        @php
                            $colors = [
                                'assigned' => 'bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20',
                                'submitted' => 'bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20',
                                'approved' => 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20',
                                'rejected' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20',
                            ];
                            $status = $task->isComplete() ? 'approved' : $task->status;
                        @endphp
                        <span class="px-2 py-1 rounded-md ring-1 ring-inset ring-gray-500/10 text-xs font-medium {{ $colors[$status] ?? 'bg-gray-100' }} capitalize whitespace-nowrap ml-2">
                            {{ $task->isComplete() ? 'Completed' : $status }}
                        </span>
                    </div>
                    
                    <p class="text-sm text-indigo-600 font-medium mt-1">
                        {{ $task->project ? 'Project: ' . $task->project->name : 'General Task' }}
                    </p>
                    
                    <div class="mt-4 text-sm text-gray-700 bg-gray-50 p-3 rounded-md border border-gray-100 min-h-[4rem] whitespace-pre-line">
                        {{ $task->description }}
                    </div>

                    <!-- Progress Tracking -->
                    <div class="mt-4">
                        @php
                            $target = $task->target_count ?: 1;
                            $approvedPct = min(100, ($task->approvedCount() / $target) * 100);
                            $pendingPct = min(100 - $approvedPct, ($task->pendingCount() / $target) * 100);
                        @endphp
                        <div class="flex justify-between text-xs font-medium mb-1">
                            <span class="text-gray-600">Progress</span>
                            <span class="text-blue-600">{{ $task->approvedCount() + $task->pendingCount() }} / {{ $task->target_count }} Submissions</span>
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
                    
                    @if($task->status === 'rejected' && $task->admin_feedback)
                        <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                            <h4 class="text-xs font-bold text-red-800 uppercase tracking-wider mb-1">Admin Feedback</h4>
                            <p class="text-sm text-red-700">{{ $task->admin_feedback }}</p>
                        </div>
                    @endif
                    
                </div>
                
                <div class="bg-gray-50 px-5 py-4 border-t border-gray-200 flex items-center justify-between">
                    <div>
                        @if($task->amount)
                            <span class="text-sm font-bold text-green-600 block">Earn: ₹{{ number_format($task->amount, 2) }}</span>
                        @endif
                        <div class="text-xs text-gray-500 mt-0.5">Assigned: {{ $task->created_at ? $task->created_at->format('M d, Y') : '-' }}</div>
                        <div class="text-xs text-indigo-600 font-medium">Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : ($task->created_at ? $task->created_at->format('M d, Y') : '-') }}</div>
                    </div>
                    
                    @if(!$task->isComplete())
                        <button wire:click.throttle.1000ms="openSubmitModal({{ $task->id }})" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                            Submit Daily Work
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-500 bg-white shadow-sm rounded-lg border border-gray-200">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-sm font-medium text-gray-900">No tasks registered</h3>
                <p class="mt-1 text-sm text-gray-500">You don't have any tasks matching this timeframe.</p>
            </div>
        @endforelse
    </div>
    
    <div class="mt-4">{{ $tasks->links() }}</div>

    <!-- Submit Work Modal -->
    @if($isSubmitModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="submit-modal" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$wire.closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Submit Daily Deliverable</h3>
                    
                    <div class="space-y-4">
                        <div class="bg-blue-50 border border-blue-200 p-3 rounded-md text-sm text-blue-800">
                            <strong>Note:</strong> All links you add below will be bundled as <strong>ONE deliverable</strong>. 
                            If a single post requires a Demo link, FB link, and IG link, add them all here before submitting.
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Add Link</label>
                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                <input type="text" wire:model="linkLabel" placeholder="Label (e.g. IG Reel)" class="block w-full sm:w-1/3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <input type="text" wire:model="linkUrl" placeholder="https://..." class="block w-full sm:w-2/3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <button wire:click.prevent.throttle.1000ms="addLink" type="button" class="w-full sm:w-auto inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 whitespace-nowrap">
                                    Add
                                </button>
                            </div>
                            @error('linkUrl') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        @if(count($links) > 0)
                            <div class="border rounded-md p-3 bg-gray-50">
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Links for this deliverable</h4>
                                <ul class="space-y-2">
                                    @foreach($links as $index => $link)
                                        <li class="flex flex-col sm:flex-row sm:items-center justify-between bg-white px-3 py-2 rounded border border-gray-200 gap-2 sm:gap-0">
                                            <div class="truncate w-full sm:w-auto sm:mr-4">
                                                <span class="font-medium text-xs text-gray-700">{{ $link['label'] }}:</span>
                                                <a href="{{ $link['url'] }}" target="_blank" class="block sm:inline text-sm text-indigo-600 hover:underline sm:ml-1 truncate">{{ $link['url'] }}</a>
                                            </div>
                                            <button wire:click.prevent="removeLink({{ $index }})" type="button" class="self-end sm:self-auto text-red-500 hover:text-red-700 text-xs font-medium">Remove</button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                            <textarea wire:model="notes" rows="3" placeholder="Any comments for the admin?" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                            @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse">
                    <button wire:click.throttle.3000ms="submitWork" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto" {{ empty($links) ? 'disabled' : '' }} @if(empty($links)) style="opacity: 0.5; cursor: not-allowed;" @endif>
                        Submit 1 Deliverable
                    </button>
                    <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
