<?php

use App\Http\Controllers\Api\CrmApiController;
use App\Http\Controllers\PaymentWebhookController;
use Illuminate\Support\Facades\Route;

// Public payment webhook
Route::post('/payment/webhook', [PaymentWebhookController::class, 'handleWebhook'])->name('api.hub.webhook');

// CRM Native Mobile App REST API Routes
Route::prefix('v1')->group(function () {
    // Auth & User
    Route::post('/login', [CrmApiController::class, 'login']);
    Route::get('/user', [CrmApiController::class, 'user']);
    Route::get('/dashboard', [CrmApiController::class, 'dashboard']);

    // Clients
    Route::get('/clients', [CrmApiController::class, 'getClients']);
    Route::post('/clients', [CrmApiController::class, 'storeClient']);

    // Leads
    Route::get('/leads', [CrmApiController::class, 'getLeads']);
    Route::post('/leads', [CrmApiController::class, 'storeLead']);
    Route::put('/leads/{id}/status', [CrmApiController::class, 'updateLeadStatus']);

    // Projects
    Route::get('/projects', [CrmApiController::class, 'getProjects']);
    Route::post('/projects', [CrmApiController::class, 'storeProject']);

    // Tasks & Special Tasks
    Route::get('/tasks', [CrmApiController::class, 'getTasks']);
    Route::post('/tasks', [CrmApiController::class, 'storeTask']);
    Route::get('/special-tasks', [CrmApiController::class, 'getSpecialTasks']);

    // Invoices
    Route::get('/invoices', [CrmApiController::class, 'getInvoices']);
    Route::post('/invoices', [CrmApiController::class, 'storeInvoice']);

    // Earnings & Payouts
    Route::get('/earnings', [CrmApiController::class, 'getEarnings']);
    Route::get('/payouts', [CrmApiController::class, 'getPayoutRequests']);
    Route::post('/payouts/request', [CrmApiController::class, 'requestPayout']);

    // Marketplace
    Route::get('/marketplace/products', [CrmApiController::class, 'getMarketplaceProducts']);
});
