<div class="space-y-6">
    <!-- Header and Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Employee & Team Directory</h2>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search team..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
            </div>
            <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Employee
            </button>
        </div>
    </div>

    <!-- Flash Message -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md flex items-center shadow-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800"><?php echo e(session('message')); ?></p>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Modern Data Table -->
    <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php if (isset($component)) { $__componentOriginal556ead748d96ceef08716ddf6d69e692 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal556ead748d96ceef08716ddf6d69e692 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.thead','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.thead'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php if (isset($component)) { $__componentOriginal81a8447ad92ff961a7b67f11577037a1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal81a8447ad92ff961a7b67f11577037a1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.tr','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.tr'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <?php if (isset($component)) { $__componentOriginal1291f4dfa458a95c228b06c14d34e160 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1291f4dfa458a95c228b06c14d34e160 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.th','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Employee <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1291f4dfa458a95c228b06c14d34e160)): ?>
<?php $attributes = $__attributesOriginal1291f4dfa458a95c228b06c14d34e160; ?>
<?php unset($__attributesOriginal1291f4dfa458a95c228b06c14d34e160); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1291f4dfa458a95c228b06c14d34e160)): ?>
<?php $component = $__componentOriginal1291f4dfa458a95c228b06c14d34e160; ?>
<?php unset($__componentOriginal1291f4dfa458a95c228b06c14d34e160); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal1291f4dfa458a95c228b06c14d34e160 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1291f4dfa458a95c228b06c14d34e160 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.th','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Role & Skills <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1291f4dfa458a95c228b06c14d34e160)): ?>
<?php $attributes = $__attributesOriginal1291f4dfa458a95c228b06c14d34e160; ?>
<?php unset($__attributesOriginal1291f4dfa458a95c228b06c14d34e160); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1291f4dfa458a95c228b06c14d34e160)): ?>
<?php $component = $__componentOriginal1291f4dfa458a95c228b06c14d34e160; ?>
<?php unset($__componentOriginal1291f4dfa458a95c228b06c14d34e160); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal1291f4dfa458a95c228b06c14d34e160 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1291f4dfa458a95c228b06c14d34e160 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.th','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Status <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1291f4dfa458a95c228b06c14d34e160)): ?>
<?php $attributes = $__attributesOriginal1291f4dfa458a95c228b06c14d34e160; ?>
<?php unset($__attributesOriginal1291f4dfa458a95c228b06c14d34e160); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1291f4dfa458a95c228b06c14d34e160)): ?>
<?php $component = $__componentOriginal1291f4dfa458a95c228b06c14d34e160; ?>
<?php unset($__componentOriginal1291f4dfa458a95c228b06c14d34e160); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal1291f4dfa458a95c228b06c14d34e160 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1291f4dfa458a95c228b06c14d34e160 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.th','data' => ['class' => 'text-right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-right']); ?>Actions <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1291f4dfa458a95c228b06c14d34e160)): ?>
<?php $attributes = $__attributesOriginal1291f4dfa458a95c228b06c14d34e160; ?>
<?php unset($__attributesOriginal1291f4dfa458a95c228b06c14d34e160); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1291f4dfa458a95c228b06c14d34e160)): ?>
<?php $component = $__componentOriginal1291f4dfa458a95c228b06c14d34e160; ?>
<?php unset($__componentOriginal1291f4dfa458a95c228b06c14d34e160); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal81a8447ad92ff961a7b67f11577037a1)): ?>
<?php $attributes = $__attributesOriginal81a8447ad92ff961a7b67f11577037a1; ?>
<?php unset($__attributesOriginal81a8447ad92ff961a7b67f11577037a1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal81a8447ad92ff961a7b67f11577037a1)): ?>
<?php $component = $__componentOriginal81a8447ad92ff961a7b67f11577037a1; ?>
<?php unset($__componentOriginal81a8447ad92ff961a7b67f11577037a1); ?>
<?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal556ead748d96ceef08716ddf6d69e692)): ?>
<?php $attributes = $__attributesOriginal556ead748d96ceef08716ddf6d69e692; ?>
<?php unset($__attributesOriginal556ead748d96ceef08716ddf6d69e692); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal556ead748d96ceef08716ddf6d69e692)): ?>
<?php $component = $__componentOriginal556ead748d96ceef08716ddf6d69e692; ?>
<?php unset($__componentOriginal556ead748d96ceef08716ddf6d69e692); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal9dc89a79c8e61a09680c7c0afd0923b0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9dc89a79c8e61a09680c7c0afd0923b0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.tbody','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.tbody'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php if (isset($component)) { $__componentOriginal81a8447ad92ff961a7b67f11577037a1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal81a8447ad92ff961a7b67f11577037a1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.tr','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.tr'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <?php if (isset($component)) { $__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.td','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 relative">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->profile_picture): ?>
                                            <img class="h-10 w-10 rounded-full object-cover border border-gray-100 shadow-sm" src="<?php echo e($user->profile_picture); ?>" alt="">
                                        <?php else: ?>
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-indigo-100 to-indigo-50 flex items-center justify-center text-indigo-700 font-bold border border-gray-100 shadow-sm">
                                                <?php echo e(substr($user->name, 0, 1)); ?>

                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php
                                            $dotColors = [
                                                'Active' => 'bg-green-500 ring-white',
                                                'Inactive' => 'bg-gray-400 ring-white',
                                                'Suspended' => 'bg-red-500 ring-white',
                                            ];
                                            $dotClass = $dotColors[$user->status ?? 'Active'] ?? 'bg-green-500 ring-white';
                                        ?>
                                        <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 <?php echo e($dotClass); ?>"></span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900"><?php echo e($user->name); ?></div>
                                        <div class="text-xs text-gray-500 flex items-center mt-0.5">
                                            <span><?php echo e($user->email); ?></span>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->mobile): ?>
                                                <span class="mx-1.5 text-gray-300">•</span>
                                                <span><?php echo e($user->mobile); ?></span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b)): ?>
<?php $attributes = $__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b; ?>
<?php unset($__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b)): ?>
<?php $component = $__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b; ?>
<?php unset($__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.td','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">
                                        <?php echo e($user->roles->first()?->name ?? 'No Role'); ?>

                                    </span>
                                    <div class="flex items-center mt-1 space-x-2">
                                        <span class="text-xs text-gray-500"><?php echo e($user->employment_type ?? 'Staff'); ?></span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->skills): ?>
                                            <span class="text-gray-300">•</span>
                                            <?php
                                                $skills = is_array($user->skills) ? $user->skills : explode(',', $user->skills);
                                            ?>
                                            <div class="flex -space-x-1">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = array_slice($skills, 0, 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="inline-block px-2 py-0.5 text-[10px] font-medium bg-gray-100 text-gray-600 border border-gray-200 rounded-md shadow-sm z-10"><?php echo e(trim($skill)); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($skills) > 2): ?>
                                                    <span class="inline-block px-2 py-0.5 text-[10px] font-medium bg-gray-50 text-gray-500 border border-gray-200 rounded-md shadow-sm z-0">+<?php echo e(count($skills) - 2); ?></span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b)): ?>
<?php $attributes = $__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b; ?>
<?php unset($__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b)): ?>
<?php $component = $__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b; ?>
<?php unset($__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.td','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <?php
                                    $statusColors = [
                                        'Active' => 'bg-green-50 text-green-700 ring-green-600/20',
                                        'Inactive' => 'bg-gray-50 text-gray-600 ring-gray-500/10',
                                        'Suspended' => 'bg-red-50 text-red-700 ring-red-600/10',
                                    ];
                                    $badgeClass = $statusColors[$user->status ?? 'Active'] ?? 'bg-green-50 text-green-700 ring-green-600/20';
                                ?>
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset <?php echo e($badgeClass); ?>">
                                    <?php echo e($user->status ?? 'Active'); ?>

                                </span>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b)): ?>
<?php $attributes = $__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b; ?>
<?php unset($__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b)): ?>
<?php $component = $__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b; ?>
<?php unset($__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.td','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <div class="flex items-center justify-end space-x-2">
                                    <button wire:click="edit(<?php echo e($user->id); ?>)" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors" title="Edit Profile">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete(<?php echo e($user->id); ?>)" wire:confirm="Are you sure you want to delete this user?" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Delete User">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b)): ?>
<?php $attributes = $__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b; ?>
<?php unset($__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b)): ?>
<?php $component = $__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b; ?>
<?php unset($__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal81a8447ad92ff961a7b67f11577037a1)): ?>
<?php $attributes = $__attributesOriginal81a8447ad92ff961a7b67f11577037a1; ?>
<?php unset($__attributesOriginal81a8447ad92ff961a7b67f11577037a1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal81a8447ad92ff961a7b67f11577037a1)): ?>
<?php $component = $__componentOriginal81a8447ad92ff961a7b67f11577037a1; ?>
<?php unset($__componentOriginal81a8447ad92ff961a7b67f11577037a1); ?>
<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php if (isset($component)) { $__componentOriginal81a8447ad92ff961a7b67f11577037a1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal81a8447ad92ff961a7b67f11577037a1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.tr','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.tr'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <?php if (isset($component)) { $__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.td','data' => ['colspan' => '4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['colspan' => '4']); ?>
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <h3 class="mt-4 text-sm font-medium text-gray-900">No team members found</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating a new employee profile.</p>
                                <div class="mt-6">
                                    <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        New Employee
                                    </button>
                                </div>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b)): ?>
<?php $attributes = $__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b; ?>
<?php unset($__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b)): ?>
<?php $component = $__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b; ?>
<?php unset($__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal81a8447ad92ff961a7b67f11577037a1)): ?>
<?php $attributes = $__attributesOriginal81a8447ad92ff961a7b67f11577037a1; ?>
<?php unset($__attributesOriginal81a8447ad92ff961a7b67f11577037a1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal81a8447ad92ff961a7b67f11577037a1)): ?>
<?php $component = $__componentOriginal81a8447ad92ff961a7b67f11577037a1; ?>
<?php unset($__componentOriginal81a8447ad92ff961a7b67f11577037a1); ?>
<?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9dc89a79c8e61a09680c7c0afd0923b0)): ?>
<?php $attributes = $__attributesOriginal9dc89a79c8e61a09680c7c0afd0923b0; ?>
<?php unset($__attributesOriginal9dc89a79c8e61a09680c7c0afd0923b0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9dc89a79c8e61a09680c7c0afd0923b0)): ?>
<?php $component = $__componentOriginal9dc89a79c8e61a09680c7c0afd0923b0; ?>
<?php unset($__componentOriginal9dc89a79c8e61a09680c7c0afd0923b0); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $attributes = $__attributesOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $component = $__componentOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__componentOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
    
    <div class="mt-6">
        <?php echo e($users->links()); ?>

    </div>

    <!-- Modal -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isModalOpen): ?>
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 overflow-y-auto max-h-[80vh]">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 border-b pb-2" id="modal-title">
                                <?php echo e($user_id ? 'Edit Employee Profile' : 'Create New Employee'); ?>

                            </h3>
                            
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <!-- Column 1: Basic Info & Auth -->
                                <div class="space-y-4">
                                    <h4 class="text-sm font-semibold text-gray-500 uppercase">Basic Information</h4>
                                    
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                        <input type="text" wire:model="name" id="name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address (Login)</label>
                                        <input type="email" wire:model="email" id="email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    
                                    <div>
                                        <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                                        <input type="text" inputmode="numeric" pattern="[6-9][0-9]{9}" maxlength="10" wire:model="mobile" id="mobile" placeholder="9876543210" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <div>
                                        <label for="pin" class="block text-sm font-medium text-gray-700">
                                            PIN (4-8 digits) <?php echo e($user_id ? '(Leave empty to keep current)' : ''); ?>

                                        </label>
                                        <div class="mt-1 relative" x-data="{ show: false }">
                                            <input :type="show ? 'text' : 'password'" inputmode="numeric" wire:model="pin" id="pin" placeholder="e.g. 123456" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md pr-10">
                                            <button type="button" @click="show = !show" :aria-label="show ? 'Hide password' : 'Show password'" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                </svg>
                                            </button>
                                        </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['pin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    
                                    <div>
                                        <label for="role" class="block text-sm font-medium text-gray-700">System Role</label>
                                        <select wire:model="role" id="role" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="">Select a Role</option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($r->name); ?>"><?php echo e($r->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </select>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700">Employee Status</label>
                                        <select wire:model="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                            <option value="Suspended">Suspended</option>
                                        </select>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Column 2: Employment & Banking -->
                                <div class="space-y-4">
                                    <h4 class="text-sm font-semibold text-gray-500 uppercase">Employment Details</h4>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="employment_type" class="block text-sm font-medium text-gray-700">Employment Type</label>
                                            <select wire:model="employment_type" id="employment_type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">-- Select --</option>
                                                <option value="Full-time">Full-time</option>
                                                <option value="Part-time">Part-time</option>
                                                <option value="Contract">Contract (Freelancer)</option>
                                                <option value="Intern">Intern</option>
                                                <option value="Freelancer">Freelancer</option>
                                            </select>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['employment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div>
                                            <label for="joining_date" class="block text-sm font-medium text-gray-700">Joining Date</label>
                                            <input type="date" wire:model="joining_date" id="joining_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['joining_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="skills" class="block text-sm font-medium text-gray-700">Skills (Comma separated)</label>
                                        <input type="text" wire:model="skills" id="skills" placeholder="e.g. React, Laravel, Design, Sales" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['skills'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <h4 class="text-sm font-semibold text-gray-500 uppercase pt-4 border-t mt-4">Bank Details</h4>

                                    <div>
                                        <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name</label>
                                        <input type="text" wire:model="bank_name" id="bank_name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['bank_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <div>
                                        <label for="account_name" class="block text-sm font-medium text-gray-700">Account Holder Name</label>
                                        <input type="text" wire:model="account_name" id="account_name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['account_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="account_number" class="block text-sm font-medium text-gray-700">Account Number</label>
                                            <input type="text" wire:model="account_number" id="account_number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div>
                                            <label for="ifsc_code" class="block text-sm font-medium text-gray-700">IFSC Code</label>
                                            <input type="text" wire:model="ifsc_code" id="ifsc_code" placeholder="HBSK0001234" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md uppercase">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ifsc_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                    
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                    <button wire:click.prevent="store()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-6 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Profile
                    </button>
                    <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH /home/ubuntu/Desktop/flipflop/payout-system/resources/views/livewire/user-management.blade.php ENDPATH**/ ?>