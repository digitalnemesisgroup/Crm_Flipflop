<nav class="p-4 space-y-1">
    <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('dashboard') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        Dashboard
    </a>

    <a href="<?php echo e(route('clients')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('clients') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('clients') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
        Clients
    </a>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'SUPER ADMIN|ADMIN')): ?>
    <div class="pt-4 pb-2">
        <p class="px-4 text-xs font-semibold tracking-wider text-gray-400 uppercase">Master Data</p>
    </div>
    
    <a href="<?php echo e(route('users')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('users') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('users') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        Team Directory
    </a>

    <div class="pt-4 pb-2">
        <p class="px-4 text-xs font-semibold tracking-wider text-gray-400 uppercase">Work & Projects</p>
    </div>
    
    <a href="<?php echo e(route('leads')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('leads') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('leads') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
        </svg>
        Leads
    </a>
    <a href="<?php echo e(route('projects')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('projects') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('projects') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
        </svg>
        Projects
    </a>
        <a href="<?php echo e(route('tasks.manage')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('tasks.*') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('tasks.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
        </svg>
        Task Manager
    </a>
    <a href="<?php echo e(route('form-templates')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('form-templates') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('form-templates') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
        </svg>
        Form Templates
    </a>
    <a href="<?php echo e(route('special-tasks')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('special-tasks') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('special-tasks') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        Special Tasks (Forms)
    </a>
    
    <div class="pt-4 pb-2">
        <p class="px-4 text-xs font-semibold tracking-wider text-gray-400 uppercase">Financials</p>
    </div>
    
    <a href="<?php echo e(route('invoices')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('invoices') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('invoices') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
        </svg>
        Invoices
    </a>
    <a href="<?php echo e(route('earnings')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('earnings') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('earnings') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Earnings
    </a>
    <a href="<?php echo e(route('payouts')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('payouts') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('payouts') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        Payouts
    </a>
    
    <div class="pt-4 pb-2">
        <p class="px-4 text-xs font-semibold tracking-wider text-gray-400 uppercase">Analytics & Intelligence</p>
    </div>
    
    <a href="<?php echo e(route('reports')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('reports') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('reports') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
        Reports
    </a>
    <a href="<?php echo e(route('activity-log')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('activity-log') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('activity-log') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
        </svg>
        Activity Log
    </a>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'EMPLOYEE|FREELANCER')): ?>
    <div class="pt-4 pb-2">
        <p class="px-4 text-xs font-semibold tracking-wider text-gray-400 uppercase">My Workspace</p>
    </div>
    
        <a href="<?php echo e(route('my-tasks')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('my-tasks') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('my-tasks') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        My Tasks
    </a>
    <a href="<?php echo e(route('my-special-tasks')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('my-special-tasks') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('my-special-tasks') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        My Special Tasks
    </a>
    <a href="<?php echo e(route('my-leads')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('my-leads') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('my-leads') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        My Work / Leads
    </a>
    <a href="<?php echo e(route('activity-log')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('activity-log') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('activity-log') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
        </svg>
        My Activity Log
    </a>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->earnings()->sum('amount') > 0): ?>
    <a href="<?php echo e(route('my-earnings')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('my-earnings') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('my-earnings') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        My Earnings & Payout
    </a>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    <a href="<?php echo e(route('bank-accounts')); ?>" class="flex items-center px-4 py-3 mb-1 text-sm font-medium transition-all duration-200 <?php echo e(request()->routeIs('bank-accounts') ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md shadow-blue-500/30 rounded-xl translate-x-1' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl hover:translate-x-1'); ?> group">
        <svg class="w-5 h-5 mr-3 <?php echo e(request()->routeIs('bank-accounts') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
        </svg>
        Bank Accounts
    </a>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</nav>
<?php /**PATH /home/ubuntu/Desktop/flipflop/payout-system/resources/views/components/sidebar-nav.blade.php ENDPATH**/ ?>