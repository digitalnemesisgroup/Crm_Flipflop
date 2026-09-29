<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PayoutRequest;
use App\Models\User;
use Carbon\Carbon;

class AdminReports extends Component
{
    public $dateFilter = 'this_month';
    public $startDate;
    public $endDate;
    public $activeTab = 'projects';

    public function mount()
    {
        $this->applyDateFilter();
    }

    public function updatedDateFilter()
    {
        $this->applyDateFilter();
    }

    private function applyDateFilter()
    {
        $now = Carbon::now();
        switch ($this->dateFilter) {
            case 'today':
                $this->startDate = $now->copy()->startOfDay()->format('Y-m-d');
                $this->endDate = $now->copy()->endOfDay()->format('Y-m-d');
                break;
            case 'this_week':
                $this->startDate = $now->copy()->startOfWeek()->format('Y-m-d');
                $this->endDate = $now->copy()->endOfWeek()->format('Y-m-d');
                break;
            case 'this_month':
                $this->startDate = $now->copy()->startOfMonth()->format('Y-m-d');
                $this->endDate = $now->copy()->endOfMonth()->format('Y-m-d');
                break;
            case 'custom':
                // Keep whatever is set, or default to this month
                if (!$this->startDate) $this->startDate = $now->copy()->startOfMonth()->format('Y-m-d');
                if (!$this->endDate) $this->endDate = $now->copy()->endOfMonth()->format('Y-m-d');
                break;
            default:
                $this->startDate = null;
                $this->endDate = null;
        }
    }

    public function render()
    {
        $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : Carbon::createFromTimestamp(0);
        $end = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : Carbon::now()->endOfDay();

        // Project Report
        $projects = Project::whereBetween('created_at', [$start, $end]);
        $projectReport = [
            'total' => (clone $projects)->count(),
            'active' => (clone $projects)->where('status', 'active')->count(),
            'completed' => (clone $projects)->where('status', 'completed')->count(),
            'cancelled' => (clone $projects)->where('status', 'cancelled')->count(),
            'value' => (clone $projects)->sum('total_budget'),
        ];

        // Client Payment Report
        $invoices = Invoice::whereBetween('issue_date', [$start, $end]);
        $totalBilling = (clone $invoices)->sum('amount');
        $received = Payment::whereBetween('payment_date', [$start, $end])->sum('amount_paid');

        // pending = total billed minus what's actually been received (from payments table)
        $pending = max(0, $totalBilling - $received);

        // overdue = sum of overdue invoices minus payments made against those invoice IDs
        $overdueInvoiceIds = (clone $invoices)->where('status', 'overdue')->pluck('id');
        $overdueTotal      = (clone $invoices)->where('status', 'overdue')->sum('amount');
        $overdueReceived   = $overdueInvoiceIds->isNotEmpty()
            ? Payment::whereIn('invoice_id', $overdueInvoiceIds)->sum('amount_paid')
            : 0;
        
        $clientPaymentReport = [
            'total_billing' => $totalBilling,
            'received'      => $received,
            'pending'       => $pending,
            'overdue'       => max(0, $overdueTotal - $overdueReceived),
        ];

        // Employee Earning Report — uses User model relationships
        $employees = User::role(['EMPLOYEE', 'FREELANCER'])->get()->map(function(User $user) use ($start, $end) {
            // Period-bound earnings and payouts
            $totalEarning = $user->earnings()
                ->whereIn('status', ['cleared', 'paid'])
                ->whereBetween('created_at', [$start, $end])
                ->sum('amount');

            $paid = $user->payoutRequests()
                ->where('status', 'paid')
                ->whereBetween('updated_at', [$start, $end])
                ->sum('amount');

            $due = max(0, $totalEarning - $paid);

            // Available is always global (current live balance, not date-filtered)
            $available = $user->availableBalance();

            // Project count via pivot — safely qualify the pivot column
            try {
                $projectCount = $user->projects()
                    ->whereBetween('project_user.created_at', [$start, $end])
                    ->count();
            } catch (\Throwable $e) {
                $projectCount = $user->projects()->count();
            }

            return (object)[
                'name'          => $user->name,
                'projects'      => $projectCount,
                'total_earning' => $totalEarning,
                'paid'          => $paid,
                'due'           => $due,
                'available'     => $available,
            ];
        });

        // Payout Report
        $payouts = PayoutRequest::whereBetween('created_at', [$start, $end]);
        $payoutReport = [
            'requested' => (clone $payouts)->sum('amount'),
            'approved' => (clone $payouts)->where('status', 'approved')->sum('amount'),
            'paid' => (clone $payouts)->where('status', 'paid')->sum('amount'),
            'pending' => (clone $payouts)->where('status', 'pending')->sum('amount'),
            'rejected' => (clone $payouts)->where('status', 'rejected')->sum('amount'),
        ];

        return view('livewire.admin-reports', [
            'projectReport' => $projectReport,
            'clientPaymentReport' => $clientPaymentReport,
            'employeeReport' => $employees,
            'payoutReport' => $payoutReport,
        ])->layout('layouts.app', ['header' => 'System Reports']);
    }
}
