<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Payment;
use App\Models\Earning;
use App\Models\PayoutRequest;
use App\Models\PayoutTransaction;
use App\Models\Client;
use App\Models\User;
use App\Models\Task;
use App\Models\SpecialTask;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();

        // ── Role-based dispatch ──────────────────────────────────────────────
        if ($user->hasAnyRole(['EMPLOYEE', 'FREELANCER'])) {
            return $this->renderEmployeeDashboard($user);
        }

        return $this->renderAdminDashboard();
    }

    /**
     * Full company-wide overview for SUPER ADMIN / ADMIN / MANAGER / ACCOUNT TEAM.
     */
    private function renderAdminDashboard()
    {
        // ── Project Aggregations ─────────────────────────────────────────────
        $totalProjects     = Project::count();
        $activeProjects    = Project::where('status', 'active')->count();
        $completedProjects = Project::where('status', 'completed')->count();
        $totalProjectValue = Project::sum('total_budget');

        // ── Client Payment Aggregations ──────────────────────────────────────
        $clientPaymentReceived = Payment::sum('amount_paid');

        // ── Employee Payout Aggregations ─────────────────────────────────────
        $employeePayout = PayoutRequest::where('status', 'paid')->sum('amount');
        $pendingPayout  = PayoutRequest::whereIn('status', ['pending', 'approved'])->sum('amount');

        $totalClearedEarnings = Earning::whereIn('status', ['cleared', 'paid'])->sum('amount');
        $availablePayable     = max(0, $totalClearedEarnings - $employeePayout - $pendingPayout);

        // ── Chart Data: last 6 months ────────────────────────────────────────
        $months      = [];
        $revenueData = [];
        $payoutData  = [];

        for ($i = 5; $i >= 0; $i--) {
            $date     = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');

            $revenueData[] = (float) Payment::whereYear('payment_date', $date->year)
                                ->whereMonth('payment_date', $date->month)
                                ->sum('amount_paid');

            $payoutData[] = (float) PayoutTransaction::whereYear('transaction_date', $date->year)
                                ->whereMonth('transaction_date', $date->month)
                                ->sum('amount');
        }

        // ── Recent Activity ──────────────────────────────────────────────────
        $recentActivity = \App\Models\ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

                $totalClients   = Client::count();
        $totalEmployees = User::role(['EMPLOYEE', 'FREELANCER'])->count();
        
        $specialTasksAssigned = SpecialTask::count();
        $specialTasksPending = SpecialTask::whereIn('status', ['submitted', 'assigned'])->count();

        // ── Additional Metrics ───────────────────────────────────────────────
        $avgProjectValue = $totalProjects > 0 ? $totalProjectValue / $totalProjects : 0;
        
        $projectStatusBreakdown = Project::select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
            
        $topClients = Client::withSum('projects', 'total_budget')
            ->orderByDesc('projects_sum_total_budget')
            ->take(5)
            ->get();
            
        $totalAdminTasks = Task::count();
        $completedAdminTasks = Task::where('status', 'approved')->count();
        $taskCompletionRate = $totalAdminTasks > 0 ? round(($completedAdminTasks / $totalAdminTasks) * 100) : 0;

        return view('livewire.dashboard', [
            'view'                 => 'admin',
            'totalProjects'        => $totalProjects,
            'activeProjects'       => $activeProjects,
            'completedProjects'    => $completedProjects,
            'totalProjectValue'    => $totalProjectValue,
            'clientPaymentReceived'=> $clientPaymentReceived,
            'employeePayout'       => $employeePayout,
            'pendingPayout'        => $pendingPayout,
            'availablePayable'     => $availablePayable,
            'totalClearedEarnings' => $totalClearedEarnings,
            'totalClients'         => $totalClients,
            'totalEmployees'       => $totalEmployees,
            'specialTasksAssigned' => $specialTasksAssigned,
            'specialTasksPending'  => $specialTasksPending,
            'recentActivity'       => $recentActivity,
            'chartMonths'          => $months,
            'chartRevenue'         => $revenueData,
            'chartPayouts'         => $payoutData,
            'avgProjectValue'      => $avgProjectValue,
            'projectStatusBreakdown'=> $projectStatusBreakdown,
            'topClients'           => $topClients,
            'totalAdminTasks'      => $totalAdminTasks,
            'completedAdminTasks'  => $completedAdminTasks,
            'taskCompletionRate'   => $taskCompletionRate,
        ])->layout('layouts.app', ['header' => 'Dashboard Overview']);
    }

    /**
     * Personal, scoped view for EMPLOYEE / FREELANCER.
     * Shows only this user's tasks, earnings and payouts — no company-wide financials.
     */
    private function renderEmployeeDashboard(User $user)
    {
        $userId = $user->id;

                // ── Tasks scoped to this user ────────────────────────────────────────
        $myTasks        = Task::where('user_id', $userId)->count();
        $pendingTasks   = Task::where('user_id', $userId)
            ->whereIn('status', ['assigned', 'submitted', 'rejected'])
            ->count();
        $completedTasks = Task::where('user_id', $userId)
            ->where('status', 'approved')
            ->count();
            
        $mySpecialTasks = SpecialTask::where('user_id', $userId)->count();
        $pendingSpecialTasks = SpecialTask::where('user_id', $userId)
            ->whereIn('status', ['assigned', 'submitted'])
            ->count();
        $pendingTasks   = Task::where('user_id', $userId)
            ->whereIn('status', ['assigned', 'submitted', 'rejected'])
            ->count();
        $completedTasks = Task::where('user_id', $userId)
            ->where('status', 'approved')
            ->count();

        // ── My earnings scoped to this user ─────────────────────────────────
        $myTotalEarnings     = Earning::where('user_id', $userId)->sum('amount');
        $myClearedEarnings   = Earning::where('user_id', $userId)
            ->whereIn('status', ['cleared', 'paid'])
            ->sum('amount');
        $myPendingEarnings   = Earning::where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');
        $myAvailablePayable  = Earning::where('user_id', $userId)
            ->whereIn('status', ['cleared', 'paid'])
            ->sum('amount')
            - PayoutRequest::where('user_id', $userId)->sum('amount');
        $myAvailablePayable  = max(0, $myAvailablePayable);

        // ── My payouts scoped to this user ───────────────────────────────────
        $myPaidOut    = PayoutRequest::where('user_id', $userId)->where('status', 'paid')->sum('amount');
        $myPayoutReq  = PayoutRequest::where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');

        // ── My recent tasks (next 5) ────────────────────────────────────────
        $myRecentTasks = Task::where('user_id', $userId)
            ->whereIn('status', ['assigned', 'submitted', 'rejected'])
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        return view('livewire.dashboard', [
            'view'              => 'employee',
            'myTasks'           => $myTasks,
                        'pendingTasks'      => $pendingTasks,
            'completedTasks'    => $completedTasks,
            'mySpecialTasks'    => $mySpecialTasks,
            'pendingSpecialTasks' => $pendingSpecialTasks,
            'myTotalEarnings'   => $myTotalEarnings,
            'myClearedEarnings' => $myClearedEarnings,
            'myPendingEarnings' => $myPendingEarnings,
            'myAvailablePayable'=> $myAvailablePayable,
            'myPaidOut'         => $myPaidOut,
            'myPayoutReq'       => $myPayoutReq,
            'myRecentTasks'     => $myRecentTasks,
        ])->layout('layouts.app', ['header' => 'My Dashboard']);
    }
}