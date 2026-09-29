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

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md shadow-sm">
            <p class="text-sm font-medium text-green-800"><?php echo e(session('message')); ?></p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 flex flex-col">
                <div class="p-5 flex-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 truncate"><?php echo e($task->title); ?></h3>
                            <p class="text-xs text-gray-500 mt-1">Form: <?php echo e($task->formTemplate->name); ?></p>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->status === 'assigned'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20">Assigned</span>
                        <?php elseif($task->status === 'submitted'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20">In Progress</span>
                        <?php elseif($task->status === 'approved'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20">Complete</span>
                        <?php elseif($task->status === 'rejected' || $task->submissions->where('status', 'rejected')->count() > 0): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20">Rejected / Resubmit</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    
                    <p class="mt-3 text-sm text-gray-600 line-clamp-2"><?php echo e($task->description); ?></p>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($task->status === 'rejected' || $task->submissions->where('status', 'rejected')->count() > 0)): ?>
                        <?php
                            $rejectedSub = $task->submissions->where('status', 'rejected')->last();
                        ?>
                        <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-md">
                            <h4 class="text-xs font-bold text-red-800 uppercase tracking-wider mb-1">Admin Feedback (Rejected)</h4>
                            <p class="text-xs text-red-700"><?php echo e($rejectedSub->admin_feedback ?? 'Your previous submission was rejected. Please re-fill and submit again.'); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <div class="mt-4">
                        <?php
                            $approvedPct = min(100, ($task->approvedCount() / max(1, $task->target_count)) * 100);
                            $pendingPct = min(100 - $approvedPct, ($task->pendingCount() / max(1, $task->target_count)) * 100);
                        ?>
                        <div class="flex justify-between text-xs font-medium text-gray-500 mb-1">
                            <span>Progress</span>
                            <span><?php echo e($task->approvedCount() + $task->pendingCount()); ?> / <?php echo e($task->target_count); ?></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 flex overflow-hidden">
                            <div class="bg-green-500 h-2" style="width: <?php echo e($approvedPct); ?>%"></div>
                            <div class="bg-blue-500 h-2" style="width: <?php echo e($pendingPct); ?>%"></div>
                        </div>
                        <div class="flex justify-between text-xs mt-1">
                            <span class="text-green-600"><?php echo e($task->approvedCount()); ?> Verified</span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->pendingCount() > 0): ?>
                                <span class="text-blue-600"><?php echo e($task->pendingCount()); ?> Pending</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-5 py-4 border-t border-gray-200 flex items-center justify-between">
                    <div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->amount): ?>
                            <span class="text-sm font-bold text-green-600 block">Earn: ₹<?php echo e(number_format($task->amount, 2)); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="text-xs text-gray-500 mt-0.5">Assigned: <?php echo e($task->created_at ? $task->created_at->format('M d, Y') : '-'); ?></div>
                        <div class="text-xs text-indigo-600 font-medium">Due: <?php echo e($task->due_date ? $task->due_date->format('M d, Y') : ($task->created_at ? $task->created_at->format('M d, Y') : '-')); ?></div>
                    </div>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$task->isComplete() && ($task->approvedCount() + $task->pendingCount()) < $task->target_count): ?>
                        <button wire:click="openSubmitModal(<?php echo e($task->id); ?>)" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                            Fill Form
                            <?php echo e(($task->status === 'rejected' || $task->submissions->where('status', 'rejected')->count() > 0) ? 'Re-upload / Resubmit' : 'Fill Form'); ?>

                        </button>
                    <?php elseif(!$task->isComplete()): ?>
                        <span class="text-xs text-gray-500 italic">Pending Admin Review</span>
                        <button wire:click="openSubmitModal(<?php echo e($task->id); ?>)" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700">
                            Re-upload / Resubmit
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full py-12 text-center text-gray-500 bg-white shadow-sm rounded-lg border border-gray-200">
                You have no special tasks assigned.
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    
    <div class="mt-4"><?php echo e($tasks->links()); ?></div>

    <!-- Submit Modal -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isSubmitModalOpen && $activeTask): ?>
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('isSubmitModalOpen', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                <form wire:submit.prevent="submitForm">
                    <div class="bg-indigo-600 px-4 py-4 sm:px-6">
                        <h3 class="text-lg font-medium text-white"><?php echo e($activeTask->title); ?> - Form Entry</h3>
                        <p class="text-indigo-200 text-sm mt-1">Please fill out this form carefully. Fields marked with * are required.</p>
                    </div>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 space-y-5">
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $activeTask->formTemplate->schema; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <?php echo e($field['label']); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field['required']): ?> <span class="text-red-500">*</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </label>
                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field['type'] == 'text'): ?>
                                    <input type="text" wire:model="formData.<?php echo e($field['id']); ?>" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                
                                <?php elseif($field['type'] == 'textarea'): ?>
                                    <textarea wire:model="formData.<?php echo e($field['id']); ?>" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                
                                <?php elseif($field['type'] == 'number'): ?>
                                    <input type="number" wire:model="formData.<?php echo e($field['id']); ?>" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                
                                <?php elseif($field['type'] == 'date'): ?>
                                    <input type="date" wire:model="formData.<?php echo e($field['id']); ?>" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                
                                <?php elseif($field['type'] == 'time'): ?>
                                    <input type="time" wire:model="formData.<?php echo e($field['id']); ?>" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                
                                <?php elseif($field['type'] == 'dropdown'): ?>
                                    <select wire:model="formData.<?php echo e($field['id']); ?>" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Select an option</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = explode(',', $field['options']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e(trim($option)); ?>"><?php echo e(trim($option)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                
                                <?php elseif($field['type'] == 'radio'): ?>
                                    <div class="space-y-2 mt-2">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = explode(',', $field['options']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-center">
                                                <input type="radio" wire:model="formData.<?php echo e($field['id']); ?>" value="<?php echo e(trim($option)); ?>" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                <label class="ml-3 block text-sm font-medium text-gray-700"><?php echo e(trim($option)); ?></label>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    
                                <?php elseif($field['type'] == 'checkbox'): ?>
                                    <div class="space-y-2 mt-2">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = explode(',', $field['options']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-center">
                                                <input type="checkbox" wire:model="formData.<?php echo e($field['id']); ?>" value="<?php echo e(trim($option)); ?>" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                                <label class="ml-3 block text-sm font-medium text-gray-700"><?php echo e(trim($option)); ?></label>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['formData.'.$field['id']];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs block mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse border-t border-gray-200">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Submit Form</button>
                        <button type="button" wire:click="$set('isSubmitModalOpen', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH /home/ubuntu/Desktop/flipflop/payout-system/resources/views/livewire/my-special-tasks.blade.php ENDPATH**/ ?>