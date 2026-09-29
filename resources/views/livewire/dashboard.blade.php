@if($view === 'admin')
<div class="space-y-4">

    <!-- KPI Grid Row 1 -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <!-- Total Projects -->
        <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200 rounded-xl border border-gray-100">
            <div class="p-4 flex items-center">
                <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5">
                    <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="ml-4 w-0 flex-1">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Projects</dt>
                    <dd class="text-xl font-bold text-gray-900">{{ $totalProjects }}</dd>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-1.5 flex justify-between text-xs">
                <span class="text-blue-600 font-medium">{{ $activeProjects }} Active</span>
                <span class="text-green-600 font-medium">{{ $completedProjects }} Done</span>
            </div>
        </div>

        <!-- Total Project Value -->
        <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200 rounded-xl border border-gray-100">
            <div class="p-4 flex items-center">
                <div class="flex-shrink-0 bg-green-50 rounded-lg p-2.5">
                    <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4 w-0 flex-1">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Project Value</dt>
                    <dd class="text-xl font-bold text-gray-900">₹{{ number_format($totalProjectValue, 0) }}</dd>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-1.5 text-xs">
                <span class="text-gray-500">Received: </span>
                <span class="text-green-600 font-medium">₹{{ number_format($clientPaymentReceived, 0) }}</span>
            </div>
        </div>

        <!-- Employee Payout -->
        <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200 rounded-xl border border-gray-100">
            <div class="p-4 flex items-center">
                <div class="flex-shrink-0 bg-purple-50 rounded-lg p-2.5">
                    <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="ml-4 w-0 flex-1">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Payouts Paid</dt>
                    <dd class="text-xl font-bold text-gray-900">₹{{ number_format($employeePayout, 0) }}</dd>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-1.5 text-xs">
                <span class="text-gray-500">Pending: </span>
                <span class="text-yellow-600 font-medium">₹{{ number_format($pendingPayout, 0) }}</span>
            </div>
        </div>

        <!-- Available / Payable -->
        <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200 rounded-xl border border-blue-100 relative">
            <div class="absolute inset-0 bg-blue-50 opacity-20 pointer-events-none"></div>
            <div class="p-4 flex items-center relative z-10">
                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-2.5">
                    <svg class="h-5 w-5 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <div class="ml-4 w-0 flex-1">
                    <dt class="text-xs font-semibold text-blue-800 uppercase tracking-wide">Avail. Payable</dt>
                    <dd class="text-xl font-bold text-blue-700">₹{{ number_format($availablePayable, 0) }}</dd>
                </div>
            </div>
            <div class="bg-blue-50 px-4 py-1.5 text-xs text-blue-700 relative z-10 font-medium">
                Ready to be requested
            </div>
        </div>
    </div>

    <!-- KPI Row 2 — Quick Stats & New Metrics -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl p-5 text-white shadow-lg shadow-indigo-500/40 flex items-center justify-between transform transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-indigo-500/50 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-2xl pointer-events-none"></div>
            <div>
                <p class="text-xs font-medium text-indigo-100 uppercase tracking-wide">Total Clients</p>
                <p class="text-2xl font-bold mt-1">{{ $totalClients }}</p>
            </div>
            <div class="bg-white/20 p-2 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
        </div>
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 text-white shadow-lg shadow-emerald-500/40 flex items-center justify-between transform transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-500/50 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-2xl pointer-events-none"></div>
            <div>
                <p class="text-xs font-medium text-emerald-100 uppercase tracking-wide">Team Members</p>
                <p class="text-2xl font-bold mt-1">{{ $totalEmployees }}</p>
            </div>
            <div class="bg-white/20 p-2 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </div>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/40 flex items-center justify-between transform transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-500/50 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-2xl pointer-events-none"></div>
            <div>
                <p class="text-xs font-medium text-blue-100 uppercase tracking-wide">Task Completion</p>
                <p class="text-2xl font-bold mt-1">{{ $taskCompletionRate }}%</p>
            </div>
            <div class="bg-white/20 p-2 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl p-5 text-white shadow-sm flex items-center justify-between transform transition-all duration-300 hover:-translate-y-1 hover:shadow-xl relative overflow-hidden">
            <div>
                <p class="text-xs font-medium text-gray-300 uppercase tracking-wide">Avg. Project Value</p>
                <p class="text-2xl font-bold mt-1">₹{{ number_format($avgProjectValue, 0) }}</p>
            </div>
            <div class="bg-white/10 p-2 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Financial Chart -->
        <div class="lg:col-span-2 bg-white shadow-sm rounded-xl border border-gray-100 p-5">
            <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide">Financial Overview (6 Mo)</h3>
            <div class="relative h-64 w-full">
                <canvas id="financialChart"></canvas>
            </div>
        </div>

        <!-- Project Status Chart -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-5">
            <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide">Projects by Status</h3>
            <div class="relative h-64 w-full flex justify-center items-center">
                <canvas id="projectStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Data Tables & Feed -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Top Clients -->
        <div class="lg:col-span-2 bg-white shadow-sm rounded-xl border border-gray-100 p-5">
            <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide">Top Clients by Value</h3>
            <x-table>
                    <x-table.thead>
                        <x-table.tr>
                            <x-table.th>Client</x-table.th>
                            <x-table.th>Contact</x-table.th>
                            <x-table.th>Total Value</x-table.th>
                        </x-table.tr>
                    </x-table.thead>
                    <x-table.tbody>
                        @forelse($topClients as $client)
                            <x-table.tr>
                                <x-table.td>{{ $client->name }}</x-table.td>
                                <x-table.td>{{ $client->email ?? 'N/A' }}</x-table.td>
                                <x-table.td>₹{{ number_format($client->projects_sum_total_budget, 0) }}</x-table.td>
                            </x-table.tr>
                        @empty
                            <x-table.tr>
                                <x-table.td colspan="3">No clients found.</x-table.td>
                            </x-table.tr>
                        @endforelse
                    </x-table.tbody>
                </x-table>

        <!-- Recent Activity Feed -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-5">
            <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide">Recent Activity</h3>
            <div class="flow-root">
                <ul class="-mb-4 space-y-3">
                    @forelse($recentActivity as $log)
                        <li class="relative">
                            <div class="flex items-start space-x-3">
                                <div class="h-7 w-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-700 text-xs font-bold flex-shrink-0 border border-gray-200">
                                    {{ substr($log->user->name ?? 'S', 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs text-gray-800 font-medium">{{ $log->action }}</p>
                                    @if($log->new_value)
                                        <p class="text-[11px] text-gray-500 truncate mt-0.5">{{ $log->new_value }}</p>
                                    @endif
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="text-xs text-gray-400 italic">No activity yet.</li>
                    @endforelse
                </ul>
            </div>
            @if($recentActivity->count() > 0)
                <div class="mt-4 border-t border-gray-100 pt-3">
                    <a href="{{ route('activity-log') }}" class="text-indigo-600 text-xs font-semibold hover:text-indigo-800">
                        View Full Log &rarr;
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const ctx = document.getElementById('financialChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartMonths),
                    datasets: [
                        {
                            label: 'Received',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            borderWidth: 2,
                            pointRadius: 3,
                            data: @json($chartRevenue),
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Paid Out',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            borderColor: 'rgba(99, 102, 241, 1)',
                            borderWidth: 2,
                            pointRadius: 3,
                            data: @json($chartPayouts),
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top', labels: { boxWidth: 10, font: { size: 11 } } },
                        tooltip: {
                            callbacks: {
                                label: ctx => ctx.dataset.label + ': ' + new Intl.NumberFormat('en-IN').format(ctx.parsed.y)
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                        y: {
                            beginAtZero: true,
                            border: { display: false },
                            ticks: { font: { size: 11 }, callback: v => new Intl.NumberFormat('en-IN').format(v) }
                        }
                    }
                }
            });

            const statusCtx = document.getElementById('projectStatusChart').getContext('2d');
            const statusData = @json($projectStatusBreakdown);
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(statusData).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
                    datasets: [{
                        data: Object.values(statusData),
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)', // green for completed/active
                            'rgba(245, 158, 11, 0.8)', // yellow for pending
                            'rgba(59, 130, 246, 0.8)', // blue
                            'rgba(239, 68, 68, 0.8)',  // red for cancelled
                            'rgba(107, 114, 128, 0.8)' // gray
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 12, font: { size: 11 } } }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
</div>
@else
<div class="space-y-4">

    <!-- Welcome strip -->
    <div class="bg-gradient-to-r from-gray-900 to-black rounded-xl p-6 text-white shadow-md relative overflow-hidden flex items-center justify-between">
        <div class="relative z-10">
            <h2 class="text-xl md:text-2xl font-bold tracking-tight">Welcome, {{ auth()->user()->name }}!</h2>
            <p class="text-gray-400 mt-1 text-sm">Here's your work and earnings summary.</p>
        </div>
        <div class="hidden sm:block relative z-10 bg-white/10 p-3 rounded-xl border border-white/10">
            <svg class="w-8 h-8 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
        </div>
    </div>

    <!-- KPI Row 1 — Tasks -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="bg-white overflow-hidden shadow-lg shadow-gray-200/50 hover:shadow-xl transition-all duration-300 rounded-2xl border border-gray-100 flex items-center p-5 transform hover:-translate-y-1">
            <div class="p-2.5 bg-gray-50 rounded-lg mr-4">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">My Tasks</p>
                <p class="text-xl font-bold text-gray-900">{{ $myTasks }}</p>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow-lg shadow-indigo-100/50 hover:shadow-xl transition-all duration-300 rounded-2xl border border-indigo-100 flex items-center p-5 transform hover:-translate-y-1">
            <div class="p-2.5 bg-indigo-50 rounded-lg mr-4">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Special Tasks</p>
                <p class="text-xl font-bold text-gray-900">{{ $mySpecialTasks }} <span class="text-sm font-normal text-gray-500">({{ $pendingSpecialTasks }} pending)</span></p>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow-lg shadow-yellow-100/50 hover:shadow-xl transition-all duration-300 rounded-2xl border border-yellow-100 flex items-center p-5 transform hover:-translate-y-1">
            <div class="p-2.5 bg-yellow-50 rounded-lg mr-4">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="flex-1">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pending</p>
                <div class="flex items-end justify-between">
                    <p class="text-xl font-bold text-yellow-600">{{ $pendingTasks }}</p>
                    <a href="{{ route('my-tasks') }}" class="text-[10px] font-medium text-yellow-700 hover:underline">View All &rarr;</a>
                </div>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow-lg shadow-green-100/50 hover:shadow-xl transition-all duration-300 rounded-2xl border border-green-100 flex items-center p-5 transform hover:-translate-y-1">
            <div class="p-2.5 bg-green-50 rounded-lg mr-4">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Completed</p>
                <p class="text-xl font-bold text-green-600">{{ $completedTasks }}</p>
            </div>
        </div>
    </div>

    <!-- KPI Row 2 — Earnings -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-4">
            <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Total Earnings</p>
            <p class="text-xl font-bold text-gray-900 mt-1">₹{{ number_format($myTotalEarnings, 0) }}</p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-blue-100 p-4">
            <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Cleared</p>
            <p class="text-xl font-bold text-blue-600 mt-1">₹{{ number_format($myClearedEarnings, 0) }}</p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-yellow-100 p-4">
            <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Pending</p>
            <p class="text-xl font-bold text-yellow-600 mt-1">₹{{ number_format($myPendingEarnings, 0) }}</p>
        </div>
        <div class="bg-blue-50 overflow-hidden shadow-sm rounded-xl border border-blue-200 p-4 relative">
            <p class="text-[11px] font-semibold text-blue-800 uppercase tracking-wide">Payable Now</p>
            <p class="text-xl font-bold text-blue-700 mt-1">₹{{ number_format($myAvailablePayable, 0) }}</p>
            <div class="absolute right-0 bottom-0 top-0 w-1/3 bg-gradient-to-l from-blue-100 to-transparent pointer-events-none"></div>
        </div>
    </div>

    <!-- My Pending Tasks + Payout summary -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        <!-- Pending tasks list -->
        <div class="lg:col-span-2 bg-white shadow-sm rounded-xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Pending Tasks</h3>
                <span class="bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingTasks }}</span>
            </div>
            <div class="space-y-2">
                @forelse($myRecentTasks as $task)
                    <div class="flex items-center justify-between border border-gray-50 rounded-lg p-2.5 bg-gray-50 hover:bg-gray-100 transition duration-150">
                        <div class="min-w-0 flex-1 mr-4">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $task->title }}</p>
                            <p class="text-[11px] text-gray-500 mt-0.5">
                                @if($task->due_date)
                                    Due {{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }}
                                @else
                                    No due date
                                @endif
                            </p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide
                            @if($task->status === 'submitted') bg-blue-100 text-blue-700
                            @elseif($task->status === 'rejected') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700 @endif">
                            {{ $task->status }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <svg class="mx-auto h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <p class="text-xs text-gray-400 mt-2">No pending tasks — you're all caught up!</p>
                    </div>
                @endforelse
            </div>
            @if($pendingTasks > 0)
                <div class="mt-4 border-t pt-4">
                    <a href="{{ route('my-tasks') }}" class="text-blue-600 text-sm font-medium hover:text-blue-800">
                        View all my tasks →
                    </a>
                </div>
            @endif
        </div>

        <!-- Payout summary -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-5 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide">Payouts</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <dt class="text-xs font-medium text-gray-500">Paid Out</dt>
                        <dd class="text-sm font-bold text-gray-900">₹{{ number_format($myPaidOut, 0) }}</dd>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <dt class="text-xs font-medium text-gray-500">Pending Requests</dt>
                        <dd class="text-sm font-bold text-yellow-600">₹{{ number_format($myPayoutReq, 0) }}</dd>
                    </div>
                    <div class="flex justify-between items-center pt-1">
                        <dt class="text-xs font-bold text-gray-700">Available to Request</dt>
                        <dd class="text-base font-extrabold text-blue-700">₹{{ number_format($myAvailablePayable, 0) }}</dd>
                    </div>
                </dl>
            </div>
            <div class="mt-5">
                @if($myTotalEarnings > 0)
                <a href="{{ route('my-earnings') }}" class="block w-full text-center px-4 py-2 border border-transparent text-xs font-bold rounded-lg text-white bg-black hover:bg-gray-800 focus:outline-none transition">
                    Manage Payouts
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
