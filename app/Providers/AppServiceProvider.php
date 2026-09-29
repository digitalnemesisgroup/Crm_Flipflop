<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            \App\Models\ActivityLog::create([
                'user_id' => $event->user->id,
                'action' => 'User Logged In',
                'new_value' => 'Email: ' . $event->user->email,
                'ip_address' => request()->ip(),
                'device' => request()->header('User-Agent'),
            ]);
        });

        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Logout::class, function ($event) {
            if ($event->user) {
                \App\Models\ActivityLog::create([
                    'user_id' => $event->user->id,
                    'action' => 'User Logged Out',
                    'new_value' => 'Email: ' . $event->user->email,
                    'ip_address' => request()->ip(),
                    'device' => request()->header('User-Agent'),
                ]);
            }
        });
    }
}
