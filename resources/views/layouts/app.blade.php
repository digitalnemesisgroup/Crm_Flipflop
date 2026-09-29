<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CRM') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('bglogo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50 flex h-screen overflow-hidden" x-data="{ mobileSidebarOpen: false, desktopSidebarOpen: true }">
        
        <!-- Desktop Sidebar -->
        <aside x-show="desktopSidebarOpen" 
               x-transition:enter="transition-all ease-in-out duration-300" 
               x-transition:enter-start="-ml-64" 
               x-transition:enter-end="ml-0" 
               x-transition:leave="transition-all ease-in-out duration-300" 
               x-transition:leave-start="ml-0" 
               x-transition:leave-end="-ml-64" 
               class="hidden w-64 bg-white border-r border-gray-100 shadow-[4px_0_24px_rgba(0,0,0,0.02)] md:flex flex-col flex-shrink-0 z-10">
            <div class="flex items-center justify-between h-20 border-b border-gray-200 px-4 py-4 flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group">
                    <img src="{{ asset('bglogo.png') }}" alt="CRM Logo" class="w-10 h-10 rounded-lg shadow-sm object-cover group-hover:scale-105 transition-transform duration-300 border border-gray-100">
                    <span class="text-lg font-bold tracking-wider text-gray-800 group-hover:text-black transition-colors">CRM</span>
                </a>
                <button @click="desktopSidebarOpen = false" class="text-gray-500 hover:text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto">
                <x-sidebar-nav />
            </div>
            <livewire:layout.sidebar-profile />
        </aside>

        <!-- Mobile Sidebar Backdrop -->
        <div x-show="mobileSidebarOpen" class="fixed inset-0 z-20 bg-gray-900 bg-opacity-50 md:hidden transition-opacity" @click="mobileSidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;"></div>

        <!-- Mobile Sidebar -->
        <aside x-show="mobileSidebarOpen" class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 md:hidden transition-transform transform flex flex-col" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" style="display: none;">
            <!-- Mobile sidebar content (same as desktop) -->
            <div class="flex items-center justify-between h-20 px-6 border-b border-gray-200 py-4 flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group">
                    <img src="{{ asset('bglogo.png') }}" alt="CRM Logo" class="w-10 h-10 rounded-lg shadow-sm object-cover border border-gray-100">
                    <span class="text-xl font-bold tracking-wider text-gray-800">CRM</span>
                </a>
                <button @click="mobileSidebarOpen = false" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto">
                <x-sidebar-nav />
            </div>
            <livewire:layout.sidebar-profile />
        </aside>

        <div class="flex flex-col flex-1 w-full overflow-hidden">
            <!-- Topbar -->
            <header class="flex items-center justify-between flex-shrink-0 h-16 px-4 sm:px-6 bg-white border-b border-gray-200">
                <div class="flex items-center">
                    <!-- Mobile Hamburger -->
                    <button @click="mobileSidebarOpen = true" class="text-gray-500 hover:text-gray-900 focus:outline-none md:hidden transition-colors">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    
                    <!-- Desktop Hamburger (Only show when sidebar is closed) -->
                    <div class="hidden md:flex items-center">
                        <button x-show="!desktopSidebarOpen" @click="desktopSidebarOpen = true" class="text-gray-600 hover:text-black focus:outline-none transition-colors p-1 rounded-md hover:bg-gray-100" style="display: none;">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        <!-- Mini Logo for Topbar when sidebar is closed -->
                        <div x-show="!desktopSidebarOpen" class="ml-4 flex items-center space-x-2" style="display: none;">
                            <img src="{{ asset('bglogo.png') }}" alt="Logo" class="w-8 h-8 rounded-md shadow-sm border border-gray-100 object-cover">
                            <span class="text-lg font-bold text-gray-800">CRM</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <livewire:notification-bell />
                    <div class="ml-4 border-l border-gray-200 pl-4">
                        <livewire:layout.navigation />
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 md:p-6 lg:p-8">
                
                <div class="max-w-[96%] mx-auto w-full">
                    {{ $slot }}
                </div>
            </main>
        </div>
        @livewireScripts
    </body>
</html>
