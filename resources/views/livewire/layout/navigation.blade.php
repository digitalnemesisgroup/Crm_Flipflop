<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="flex items-center">
    <!-- Settings Dropdown -->
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold mr-2">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                <div class="ms-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </x-slot>

        <x-slot name="content">
            <div class="px-4 py-2 border-b border-gray-100">
                <p class="text-sm font-medium text-gray-900 truncate" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></p>
                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                <div class="mt-1 flex flex-wrap gap-1">
                    @foreach (auth()->user()->roles as $role)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </div>
            </div>

            <x-dropdown-link :href="route('profile')" wire:navigate>
                {{ __('Profile') }}
            </x-dropdown-link>

            <!-- Authentication -->
            <button wire:click="logout" class="w-full text-start">
                <x-dropdown-link>
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </button>
        </x-slot>
    </x-dropdown>
</div>
