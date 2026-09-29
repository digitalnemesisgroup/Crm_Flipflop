<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="p-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">
    <div class="flex items-center mb-3">
        <div class="flex-shrink-0">
            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
        </div>
        <div class="ml-3 truncate">
            <p class="text-sm font-medium text-gray-900 truncate" x-data="{ name: '{{ auth()->user()->name }}' }" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></p>
            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
        </div>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('profile') }}" wire:navigate class="flex-1 flex justify-center items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
            Profile
        </a>
        <button wire:click="logout" class="flex-1 flex justify-center items-center px-3 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 focus:outline-none">
            Logout
        </button>
    </div>
</div>
