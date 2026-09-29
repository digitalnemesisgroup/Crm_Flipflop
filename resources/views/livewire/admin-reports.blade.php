<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Reports & Analytics</h2>
        
        <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-3 bg-white p-3 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">Filter By:</label>
                <select wire:model.live="dateFilter" class="block w-40 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="today">Today</option>
                    <option value="this_week">This Week</option>
                    <option value="this_month">This Month</option>
                    <option value="all_time">All Time</option>
                    <option value="custom">Custom Date</option>
                </select>
            </div>
            
            @if($dateFilter === 'custom')
                <div class="flex items-center space-x-2 border-t sm:border-t-0 sm:border-l border-gray-200 pt-2 sm:pt-0 sm:pl-3">
                    <input type="date" wire:model.live="startDate" class="block w-full pl-3 pr-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <span class="text-gray-500">-</span>
                    <input type="date" wire:model.live="endDate" class="block w-full pl-3 pr-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                </div>
            @endif
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-gray-200 bg-white rounded-t-lg px-4 pt-4 shadow-sm">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button wire:click="$set('activeTab', 'projects')" class="{{ $activeTab === 'projects' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Projects Report
            </button>
            <button wire:click="$set('activeTab', 'clients')" class="{{ $activeTab === 'clients' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Client Payments
            </button>
            <button wire:click="$set('activeTab', 'employees')" class="{{ $activeTab === 'employees' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Employee Earnings
            </button>
            <button wire:click="$set('activeTab', 'payouts')" class="{{ $activeTab === 'payouts' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Payouts
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div class="bg-white shadow-sm rounded-b-lg border border-t-0 border-gray-200 p-6">
        
        @if($activeTab === 'projects')
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Project Overview ({{ $startDate }} to {{ $endDate ?? 'Now' }})</h3>
                <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3 lg:grid-cols-5">
                    <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6 border border-gray-100">
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Projects</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $projectReport['total'] }}</dd>
                    </div>
                    <div class="px-4 py-5 bg-indigo-50 shadow rounded-lg overflow-hidden sm:p-6 border border-indigo-100">
                        <dt class="text-sm font-medium text-indigo-500 truncate">Active</dt>
                        <dd class="mt-1 text-3xl font-semibold text-indigo-900">{{ $projectReport['active'] }}</dd>
                    </div>
                    <div class="px-4 py-5 bg-green-50 shadow rounded-lg overflow-hidden sm:p-6 border border-green-100">
                        <dt class="text-sm font-medium text-green-500 truncate">Completed</dt>
                        <dd class="mt-1 text-3xl font-semibold text-green-900">{{ $projectReport['completed'] }}</dd>
                    </div>
                    <div class="px-4 py-5 bg-red-50 shadow rounded-lg overflow-hidden sm:p-6 border border-red-100">
                        <dt class="text-sm font-medium text-red-500 truncate">Cancelled</dt>
                        <dd class="mt-1 text-3xl font-semibold text-red-900">{{ $projectReport['cancelled'] }}</dd>
                    </div>
                    <div class="px-4 py-5 bg-gray-50 shadow rounded-lg overflow-hidden sm:p-6 border border-gray-200">
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Value</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900">₹{{ number_format($projectReport['value'], 2) }}</dd>
                    </div>
                </dl>
            </div>
        @endif

        @if($activeTab === 'clients')
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Client Payments & Billing</h3>
                <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6 border border-gray-200">
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Billing (Invoiced)</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">₹{{ number_format($clientPaymentReport['total_billing'], 2) }}</dd>
                    </div>
                    <div class="px-4 py-5 bg-green-50 shadow rounded-lg overflow-hidden sm:p-6 border border-green-100">
                        <dt class="text-sm font-medium text-green-500 truncate">Received</dt>
                        <dd class="mt-1 text-3xl font-semibold text-green-900">₹{{ number_format($clientPaymentReport['received'], 2) }}</dd>
                    </div>
                    <div class="px-4 py-5 bg-yellow-50 shadow rounded-lg overflow-hidden sm:p-6 border border-yellow-100">
                        <dt class="text-sm font-medium text-yellow-500 truncate">Pending (Billed)</dt>
                        <dd class="mt-1 text-3xl font-semibold text-yellow-900">₹{{ number_format($clientPaymentReport['pending'], 2) }}</dd>
                    </div>
                    <div class="px-4 py-5 bg-red-50 shadow rounded-lg overflow-hidden sm:p-6 border border-red-100">
                        <dt class="text-sm font-medium text-red-500 truncate">Overdue</dt>
                        <dd class="mt-1 text-3xl font-semibold text-red-900">₹{{ number_format($clientPaymentReport['overdue'], 2) }}</dd>
                    </div>
                </dl>
            </div>
        @endif

        @if($activeTab === 'employees')
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Employee Earning Report</h3>
                <x-table>
                        <x-table.thead>
                            <x-table.tr>
                                <x-table.th>Employee</x-table.th>
                                <x-table.th>Projects (Period)</x-table.th>
                                <x-table.th>Earning (Period)</x-table.th>
                                <x-table.th>Paid (Period)</x-table.th>
                                <x-table.th>Due (Period)</x-table.th>
                                <x-table.th>Available (Current)</x-table.th>
                            </x-table.tr>
                        </x-table.thead>
                        <x-table.tbody>
                            @foreach($employeeReport as $emp)
                                <x-table.tr>
                                    <x-table.td>{{ $emp->name }}</x-table.td>
                                    <x-table.td>{{ $emp->projects }}</x-table.td>
                                    <x-table.td>₹{{ number_format($emp->total_earning, 2) }}</x-table.td>
                                    <x-table.td>₹{{ number_format($emp->paid, 2) }}</x-table.td>
                                    <x-table.td>₹{{ number_format($emp->due, 2) }}</x-table.td>
                                    <x-table.td>₹{{ number_format($emp->available, 2) }}</x-table.td>
                                </x-table.tr>
                            @endforeach
                        </x-table.tbody>
                    </x-table>
        @endif

        @if($activeTab === 'payouts')
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Payout Requests Report</h3>
                <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3 lg:grid-cols-5">
                    <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6 border border-gray-200">
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Requested</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900">₹{{ number_format($payoutReport['requested'], 2) }}</dd>
                    </div>
                    <div class="px-4 py-5 bg-yellow-50 shadow rounded-lg overflow-hidden sm:p-6 border border-yellow-100">
                        <dt class="text-sm font-medium text-yellow-500 truncate">Pending</dt>
                        <dd class="mt-1 text-2xl font-semibold text-yellow-900">₹{{ number_format($payoutReport['pending'], 2) }}</dd>
                    </div>
                    <div class="px-4 py-5 bg-blue-50 shadow rounded-lg overflow-hidden sm:p-6 border border-blue-100">
                        <dt class="text-sm font-medium text-blue-500 truncate">Approved (Hold)</dt>
                        <dd class="mt-1 text-2xl font-semibold text-blue-900">₹{{ number_format($payoutReport['approved'], 2) }}</dd>
                    </div>
                    <div class="px-4 py-5 bg-green-50 shadow rounded-lg overflow-hidden sm:p-6 border border-green-100">
                        <dt class="text-sm font-medium text-green-500 truncate">Successfully Paid</dt>
                        <dd class="mt-1 text-2xl font-semibold text-green-900">₹{{ number_format($payoutReport['paid'], 2) }}</dd>
                    </div>
                    <div class="px-4 py-5 bg-red-50 shadow rounded-lg overflow-hidden sm:p-6 border border-red-100">
                        <dt class="text-sm font-medium text-red-500 truncate">Rejected</dt>
                        <dd class="mt-1 text-2xl font-semibold text-red-900">₹{{ number_format($payoutReport['rejected'], 2) }}</dd>
                    </div>
                </dl>
            </div>
        @endif

    </div>
</div>
