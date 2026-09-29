<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('dashboard', \App\Livewire\Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

// Admin & Super Admin Only Routes
Route::middleware(['auth', 'verified', 'role:SUPER ADMIN|ADMIN'])->group(function () {
    Route::get('/users', \App\Livewire\UserManagement::class)->name('users');
    
    Route::get('/leads', \App\Livewire\LeadManagement::class)->name('leads');
    Route::get('/projects', \App\Livewire\ProjectManagement::class)->name('projects');
    Route::get('/tasks/manage', \App\Livewire\TaskManagement::class)->name('tasks.manage');
    Route::get('/tasks/manage/{userId}/{date?}', \App\Livewire\UserTaskManagement::class)->name('tasks.user');
    Route::get('/tasks/assignee/{userId}/{date?}', \App\Livewire\UserTaskManagement::class)->name('tasks.assignee.date');
    Route::get('/form-templates', \App\Livewire\FormTemplateBuilder::class)->name('form-templates');
    Route::get('/special-tasks', \App\Livewire\SpecialTaskManagement::class)->name('special-tasks');
    Route::get('/invoices', \App\Livewire\InvoiceManagement::class)->name('invoices');
    Route::get('/earnings', \App\Livewire\EarningManagement::class)->name('earnings');
    Route::get('/payouts', \App\Livewire\PayoutManagement::class)->name('payouts');
    Route::get('/reports', \App\Livewire\AdminReports::class)->name('reports');
});

// Public Marketplace
Route::get('/marketplace', \App\Livewire\Marketplace::class)->name('marketplace');
Route::get('/marketplace/return', [\App\Http\Controllers\PaymentWebhookController::class, 'returnUrl'])->name('marketplace.return');

// Employee & Freelancer Routes (Shared)
Route::middleware(['auth', 'verified', 'role:SUPER ADMIN|ADMIN|EMPLOYEE|FREELANCER'])->group(function () {
    Route::get('/activity-log', \App\Livewire\ActivityLogViewer::class)->name('activity-log');
    Route::get('/clients', \App\Livewire\ClientManagement::class)->name('clients');
    Route::get('/my-leads', \App\Livewire\MyLeads::class)->name('my-leads');
    Route::get('/my-tasks', \App\Livewire\MyTasks::class)->name('my-tasks');
    Route::get('/my-special-tasks', \App\Livewire\MySpecialTasks::class)->name('my-special-tasks');
    Route::get('/my-earnings', \App\Livewire\MyEarnings::class)->name('my-earnings');
    Route::get('/bank-accounts', \App\Livewire\BankAccountManagement::class)->name('bank-accounts');
    Route::get('/projects/{id}', \App\Livewire\ProjectDetails::class)->name('projects.details');
    Route::get('/invoices/{id}/download', [\App\Http\Controllers\InvoicePdfController::class, 'download'])->name('invoices.download');
});

Route::get('/init-app', function () {
    if (request('token') !== 'flipflop_setup_2026') {
        abort(403, 'Forbidden Setup');
    }
    
    $output = "<h2>🚀 FlipFlop CRM — Setup</h2><pre>";
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output .= \Illuminate\Support\Facades\Artisan::output() . "\n";
        
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\MarketplaceProduct::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        \App\Models\MarketplaceProduct::create([
            'name' => 'WhatsApp CRM Automation Suite',
            'description' => 'Automate client updates, lead follow-ups, payment reminders, and instant status notifications via official WhatsApp API integration.',
            'price' => 1.00,
            'quantity' => 100,
            'image_url' => 'https://images.unsplash.com/photo-1611746872915-64382b5c76da?auto=format&fit=crop&q=80&w=800&h=500'
        ]);

        \App\Models\MarketplaceProduct::create([
            'name' => 'Advanced Financial & Payout Reports',
            'description' => 'Unlock deep revenue forecasting, employee performance analytics, custom tax summaries, and one-click PDF/Excel report exports.',
            'price' => 2.00,
            'quantity' => 100,
            'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=800&h=500'
        ]);

        \App\Models\MarketplaceProduct::create([
            'name' => 'SMS & Transactional Email Alert Pack',
            'description' => 'Get 10,000 SMS credits and unlimited transactional email alerts for real-time task assignments and payout updates.',
            'price' => 3.00,
            'quantity' => 100,
            'image_url' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&q=80&w=800&h=500'
        ]);

        \App\Models\MarketplaceProduct::create([
            'name' => 'Client Self-Service Portal & Invoicing',
            'description' => 'Provide a dedicated client portal for invoice downloads, project milestone tracking, and direct online payment links.',
            'price' => 4.00,
            'quantity' => 100,
            'image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=800&h=500'
        ]);
        
        $output .= "Marketplace products seeded successfully.\n";

        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        $output .= \Illuminate\Support\Facades\Artisan::output() . "\n";
        
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        $output .= \Illuminate\Support\Facades\Artisan::output() . "\n";
        
    } catch (\Exception $e) {
        $output .= "Error: " . $e->getMessage() . "\n";
    }
    return $output . "</pre>";
});
