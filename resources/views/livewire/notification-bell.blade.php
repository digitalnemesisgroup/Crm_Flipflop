<div class="relative" x-data="{ open: false }">
    <!-- Bell Button -->
    <button @click="open = !open" 
            wire:click="toggleDropdown"
            class="relative p-2 text-gray-500 hover:text-blue-600 focus:outline-none transition-colors rounded-full hover:bg-blue-50">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Panel -->
    <div x-show="open"
         @click.outside="open = false"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 z-50 overflow-hidden"
         style="display:none; top: calc(100% + 4px);">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700">
            <h3 class="text-sm font-semibold text-white">Notifications</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllRead" class="text-xs text-blue-200 hover:text-white transition-colors">
                    Mark all read
                </button>
            @endif
        </div>

        <!-- Notification List -->
        <div class="max-h-[400px] overflow-y-auto divide-y divide-gray-100">
            @forelse($notifications as $notif)
                <div wire:click="markRead({{ $notif->id }})"
                     class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors
                            {{ is_null($notif->read_at) ? 'bg-blue-50/50 border-l-2 border-blue-400' : '' }}">
                    <!-- Icon -->
                    <div class="flex-shrink-0 mt-0.5">
                        @php
                            $iconMap = [
                                'project_assigned'       => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                                'project_started'        => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                                'client_payment_received'=> ['bg' => 'bg-green-100', 'text' => 'text-green-600'],
                                'earning_updated'        => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-600'],
                                'payout_available'       => ['bg' => 'bg-teal-100', 'text' => 'text-teal-600'],
                                'payout_approved'        => ['bg' => 'bg-green-100', 'text' => 'text-green-600'],
                                'payout_rejected'        => ['bg' => 'bg-red-100', 'text' => 'text-red-600'],
                                'payout_paid'            => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                                'project_completed'      => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                                'new_work_submitted'     => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600'],
                                'lead_matured'           => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600'],
                                'new_payout_request'     => ['bg' => 'bg-pink-100', 'text' => 'text-pink-600'],
                                'client_payment_due'     => ['bg' => 'bg-red-100', 'text' => 'text-red-600'],
                            ];
                            $style = $iconMap[$notif->type] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-500'];
                        @endphp
                        <span class="w-8 h-8 rounded-full {{ $style['bg'] }} {{ $style['text'] }} flex items-center justify-center text-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                    </div>

                    <!-- Body -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $notif->title }}</p>
                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $notif->body }}</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>

                    <!-- Unread indicator -->
                    @if(is_null($notif->read_at))
                        <div class="flex-shrink-0 mt-2">
                            <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="px-4 py-10 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="text-sm text-gray-400">No notifications yet</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-100 px-4 py-2 bg-gray-50">
            <p class="text-xs text-gray-400 text-center">{{ $notifications->count() }} notifications loaded</p>
        </div>
    </div>
</div>
