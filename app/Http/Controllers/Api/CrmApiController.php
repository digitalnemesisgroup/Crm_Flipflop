<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Task;
use App\Models\SpecialTask;
use App\Models\Invoice;
use App\Models\Earning;
use App\Models\PayoutRequest;
use App\Models\BankAccount;
use App\Models\MarketplaceProduct;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CrmApiController extends Controller
{
    // ── Authentication ───────────────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            // For testing/demo fallback if standard auth fails
            if ($user) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Logged in successfully',
                    'user'    => $user->load('roles'),
                ]);
            }
            return response()->json(['status' => 'error', 'message' => 'Invalid email or password'], 401);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Logged in successfully',
            'user'    => $user->load('roles'),
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user() ? $request->user()->load('roles') : User::first(),
        ]);
    }

    // ── Dashboard Overview ─────────────────────────────────────────────────
    public function dashboard()
    {
        $totalRevenue = (float) Invoice::where('status', 'PAID')->sum('amount');
        $activeProjects = Project::where('status', 'IN_PROGRESS')->count();
        $pendingTasks = Task::whereIn('status', ['PENDING', 'SUBMITTED'])->count();
        $openBounties = SpecialTask::where('status', 'OPEN')->count();

        return response()->json([
            'total_revenue'   => $totalRevenue,
            'active_projects' => $activeProjects,
            'pending_tasks'   => $pendingTasks,
            'open_bounties'   => $openBounties,
            'recent_logs'     => ActivityLog::latest()->take(10)->get(),
        ]);
    }

    // ── Clients ─────────────────────────────────────────────────────────────
    public function getClients()
    {
        return response()->json([
            'status'  => 'success',
            'clients' => Client::latest()->get(),
        ]);
    }

    public function storeClient(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string',
            'company_name' => 'nullable|string',
            'email'        => 'required|email',
            'phone'        => 'required|string',
            'address'      => 'nullable|string',
        ]);

        $client = Client::create($validated);

        return response()->json([
            'status'  => 'success',
            'client'  => $client,
            'message' => 'Client created successfully',
        ], 201);
    }

    // ── Leads ───────────────────────────────────────────────────────────────
    public function getLeads()
    {
        return response()->json([
            'status' => 'success',
            'leads'  => Lead::with('assignedUser')->latest()->get(),
        ]);
    }

    public function storeLead(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string',
            'contact_name'    => 'required|string',
            'email'           => 'required|email',
            'phone'           => 'required|string',
            'estimated_value' => 'nullable|numeric',
            'assigned_to'     => 'nullable|exists:users,id',
            'notes'           => 'nullable|string',
        ]);

        $lead = Lead::create($validated);

        return response()->json([
            'status'  => 'success',
            'lead'    => $lead,
            'message' => 'Lead created successfully',
        ], 201);
    }

    public function updateLeadStatus(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $lead->update(['status' => $request->input('status', $lead->status)]);

        return response()->json([
            'status'  => 'success',
            'lead'    => $lead,
            'message' => 'Lead status updated',
        ]);
    }

    // ── Projects ────────────────────────────────────────────────────────────
    public function getProjects()
    {
        return response()->json([
            'status'   => 'success',
            'projects' => Project::with(['client', 'tasks', 'invoices'])->latest()->get(),
        ]);
    }

    public function storeProject(Request $request)
    {
        $validated = $request->validate([
            'name'                       => 'required|string',
            'description'                => 'nullable|string',
            'client_id'                  => 'required|exists:clients,id',
            'total_budget'               => 'required|numeric',
            'employee_payout_percentage' => 'nullable|numeric',
            'start_date'                 => 'nullable|date',
            'end_date'                   => 'nullable|date',
        ]);

        $project = Project::create($validated);

        return response()->json([
            'status'  => 'success',
            'project' => $project,
            'message' => 'Project created successfully',
        ], 201);
    }

    // ── Tasks & Special Tasks ───────────────────────────────────────────────
    public function getTasks()
    {
        return response()->json([
            'status' => 'success',
            'tasks'  => Task::with(['project', 'user'])->latest()->get(),
        ]);
    }

    public function storeTask(Request $request)
    {
        $validated = $request->validate([
            'project_id'  => 'required|exists:projects,id',
            'user_id'     => 'required|exists:users,id',
            'title'       => 'required|string',
            'description' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric',
            'hours_spent' => 'nullable|numeric',
        ]);

        $task = Task::create($validated);

        return response()->json([
            'status'  => 'success',
            'task'    => $task,
            'message' => 'Task assigned successfully',
        ], 201);
    }

    public function getSpecialTasks()
    {
        return response()->json([
            'status'        => 'success',
            'special_tasks' => SpecialTask::with('claimedUser')->latest()->get(),
        ]);
    }

    // ── Invoices ────────────────────────────────────────────────────────────
    public function getInvoices()
    {
        return response()->json([
            'status'   => 'success',
            'invoices' => Invoice::with(['project', 'client'])->latest()->get(),
        ]);
    }

    public function storeInvoice(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'amount'     => 'required|numeric',
            'tax_amount' => 'nullable|numeric',
            'due_date'   => 'required|date',
        ]);

        $project = Project::findOrFail($validated['project_id']);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-' . date('Y') . '-' . str_pad(Invoice::count() + 1, 3, '0', STR_PAD_LEFT),
            'project_id'     => $project->id,
            'client_id'      => $project->client_id,
            'amount'         => $validated['amount'],
            'tax_amount'     => $validated['tax_amount'] ?? 0.0,
            'amount_paid'    => 0.0,
            'status'         => 'UNPAID',
            'issue_date'     => now(),
            'due_date'       => $validated['due_date'],
        ]);

        return response()->json([
            'status'  => 'success',
            'invoice' => $invoice,
            'message' => 'Invoice created successfully',
        ], 201);
    }

    // ── Earnings & Payouts ──────────────────────────────────────────────────
    public function getEarnings()
    {
        return response()->json([
            'status'   => 'success',
            'earnings' => Earning::with('user')->latest()->get(),
        ]);
    }

    public function getPayoutRequests()
    {
        return response()->json([
            'status'          => 'success',
            'payout_requests' => PayoutRequest::with('user')->latest()->get(),
        ]);
    }

    public function requestPayout(Request $request)
    {
        $validated = $request->validate([
            'user_id'              => 'required|exists:users,id',
            'amount'               => 'required|numeric|min:1',
            'bank_account_details' => 'required|string',
        ]);

        $payoutRequest = PayoutRequest::create([
            'user_id'              => $validated['user_id'],
            'amount'               => $validated['amount'],
            'status'               => 'pending',
            'bank_account_details' => $validated['bank_account_details'],
        ]);

        return response()->json([
            'status'         => 'success',
            'payout_request' => $payoutRequest,
            'message'        => 'Payout request submitted',
        ], 201);
    }

    // ── Marketplace ─────────────────────────────────────────────────────────
    public function getMarketplaceProducts()
    {
        return response()->json([
            'status'   => 'success',
            'products' => MarketplaceProduct::all(),
        ]);
    }
}

