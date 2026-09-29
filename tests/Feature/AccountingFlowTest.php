<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Earning;
use App\Models\PayoutRequest;
use App\Models\PayoutTransaction;
use Spatie\Permission\Models\Role;

class AccountingFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure roles exist for tests
        if (Role::count() === 0) {
            Role::create(['name' => 'SUPER ADMIN']);
            Role::create(['name' => 'EMPLOYEE']);
        }
    }

    public function test_critical_accounting_flow()
    {
        // 1. Setup Master Data
        $admin = User::factory()->create();
        $admin->assignRole('SUPER ADMIN');

        $employee = User::factory()->create();
        $employee->assignRole('EMPLOYEE');

        $client = Client::create([
            'name' => 'Test Client',
            'email' => 'client@test.com',
            'phone' => '1234567890',
        ]);

        $project = Project::create([
            'name' => 'Test Project',
            'client_id' => $client->id,
            'status' => 'active',
            'total_budget' => 5000,
        ]);
        
        // Assign employee to project
        $project->users()->attach($employee->id, ['role_in_project' => 'Developer']);

        // 2. Client Billing & Payment
        $invoice = Invoice::create([
            'invoice_number' => 'INV-001',
            'project_id' => $project->id,
            'client_id' => $client->id,
            'issue_date' => now(),
            'due_date' => now()->addDays(14),
            'amount' => 5000,
            'status' => 'sent',
        ]);

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount_paid' => 5000,
            'payment_date' => now(),
            'payment_method' => 'Bank Transfer',
        ]);
        
        $invoice->update(['status' => 'paid']);
        
        $this->assertEquals(5000, $invoice->amount_paid);
        $this->assertEquals('paid', $invoice->fresh()->status);

        // 3. Employee Earning
        $earning = Earning::create([
            'user_id' => $employee->id,
            'project_id' => $project->id,
            'amount' => 1500,
            'date' => now(),
            'description' => 'Milestone 1 completed',
            'status' => 'pending', // Awaiting client payment verification
        ]);

        // Admin verifies client paid and clears the funds
        $earning->update(['status' => 'cleared']);
        $this->assertEquals('cleared', $earning->fresh()->status);

        // 4. Payout Request
        $payoutRequest = PayoutRequest::create([
            'user_id' => $employee->id,
            'amount' => 1500,
            'status' => 'pending',
            'notes' => 'Requesting payout for Milestone 1',
        ]);
        
        $this->assertEquals('pending', $payoutRequest->status);

        // 5. Admin Approves and Pays
        $transaction = PayoutTransaction::create([
            'payout_request_id' => $payoutRequest->id,
            'amount' => 1500,
            'transaction_date' => now(),
            'payment_method' => 'PayPal',
        ]);
        
        $payoutRequest->update(['status' => 'paid']);
        $earning->update(['status' => 'paid']); // Ledger updated
        
        $this->assertDatabaseHas('payout_transactions', [
            'payout_request_id' => $payoutRequest->id,
            'amount' => 1500,
        ]);
        
        $this->assertEquals('paid', $payoutRequest->fresh()->status);
        $this->assertEquals('paid', $earning->fresh()->status);
    }
}
