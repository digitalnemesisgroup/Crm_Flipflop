<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Activity Log</h2>
        <div class="flex items-center space-x-3">
            <!-- Search -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       placeholder="Search actions, values, users..."
                       class="block w-72 pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <!-- Date Filter -->
            <input wire:model.live="dateFilter"
                   type="date"
                   class="block py-2 px-3 border border-gray-300 rounded-md sm:text-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
    </div>

    <!-- Table -->
    <x-table>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th>Date/Time</x-table.th>
                        <x-table.th>User</x-table.th>
                        <x-table.th class="text-right">Action</x-table.th>
                        <x-table.th>Old Value</x-table.th>
                        <x-table.th>New Value</x-table.th>
                        <x-table.th>IP Address</x-table.th>
                    </x-table.tr>
                </x-table.thead>
                <x-table.tbody>
                    @forelse($logs as $log)
                        <x-table.tr>
                            <x-table.td>
                                <p class="text-xs font-medium text-gray-900">{{ $log->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</p>
                            </x-table.td>
                            <x-table.td>
                                <div class="flex items-center">
                                    <div class="h-7 w-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-bold flex-shrink-0">
                                        {{ substr($log->user->name ?? 'S', 0, 1) }}
                                    </div>
                                    <span class="ml-2 text-sm text-gray-900 truncate max-w-[100px]">{{ $log->user->name ?? 'System' }}</span>
                                </div>
                            </x-table.td>
                            <x-table.td>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-600/20">
                                    {{ $log->action }}
                                </span>
                            </x-table.td>
                            <x-table.td>
                                <span class="truncate block" title="{{ $log->old_value }}">{{ $log->old_value ?? '—' }}</span>
                            </x-table.td>
                            <x-table.td>
                                <span class="truncate block" title="{{ $log->new_value }}">{{ $log->new_value ?? '—' }}</span>
                            </x-table.td>
                            <x-table.td>
                                <span class="text-xs text-gray-400 font-mono">{{ $log->ip_address ?? '—' }}</span>
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr>
                            <x-table.td colspan="6">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="text-gray-400 text-sm">No activity records found</p>
                            </x-table.td>
                        </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table>
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $logs->links() }}
        </div>
    </div>
</div>
