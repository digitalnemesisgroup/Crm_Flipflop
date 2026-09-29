<?php

namespace App\Services;

use App\Models\SystemNotification;
use App\Models\User;
use App\Models\ActivityLog;

class NotificationService
{
    /**
     * Send a notification to a single user.
     */
    public static function send(int $userId, string $type, string $title, string $body, string $link = null): void
    {
        SystemNotification::create([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'body'    => $body,
            'link'    => $link,
        ]);
    }

    /**
     * Send a notification to all users with a specific role.
     */
    public static function sendToRole(string $role, string $type, string $title, string $body, string $link = null): void
    {
        $users = User::role($role)->get();
        foreach ($users as $user) {
            self::send($user->id, $type, $title, $body, $link);
        }
    }

    // ─── Employee Notifications ────────────────────────────────────────────────

    public static function projectAssigned(int $userId, string $projectName, int $projectId): void
    {
        self::send($userId, 'project_assigned',
            '📁 Project Assigned',
            "You have been assigned to project: {$projectName}.",
            "/projects/{$projectId}"
        );
    }

    public static function projectStarted(int $userId, string $projectName, int $projectId): void
    {
        self::send($userId, 'project_started',
            '🚀 Project Started',
            "Project '{$projectName}' has started. Let's go!",
            "/projects/{$projectId}"
        );
    }

    public static function clientPaymentReceived(int $userId, string $projectName, float $amount): void
    {
        self::send($userId, 'client_payment_received',
            '💰 Client Payment Received',
            "A client payment of " . number_format($amount, 2) . " was received for '{$projectName}'.",
            '/my-earnings'
        );
    }

    public static function earningUpdated(int $userId, float $amount): void
    {
        self::send($userId, 'earning_updated',
            '📈 Earning Updated',
            "Your earning has been updated. New credit: " . number_format($amount, 2) . ".",
            '/my-earnings'
        );
    }

    public static function payoutAvailable(int $userId, float $available): void
    {
        self::send($userId, 'payout_available',
            '✅ Payout Available',
            "" . number_format($available, 2) . " is now available for payout request.",
            '/my-earnings'
        );
    }

    public static function payoutApproved(int $userId, float $amount, string $requestId): void
    {
        self::send($userId, 'payout_approved',
            '✅ Payout Approved',
            "Your payout request #{$requestId} of " . number_format($amount, 2) . " has been approved.",
            '/my-earnings'
        );
    }

    public static function payoutRejected(int $userId, float $amount, string $reason): void
    {
        self::send($userId, 'payout_rejected',
            '❌ Payout Rejected',
            "Your payout request of " . number_format($amount, 2) . " was rejected. Reason: {$reason}",
            '/my-earnings'
        );
    }

    public static function payoutPaid(int $userId, float $amount, string $reference): void
    {
        self::send($userId, 'payout_paid',
            '🎉 Payout Paid!',
            "" . number_format($amount, 2) . " has been transferred to your bank account. Ref: {$reference}.",
            '/my-earnings'
        );
    }

    public static function projectCompleted(int $userId, string $projectName): void
    {
        self::send($userId, 'project_completed',
            '🏁 Project Completed',
            "Project '{$projectName}' has been marked as Completed. Great work!",
            '/my-earnings'
        );
    }

    // ─── Admin Notifications ───────────────────────────────────────────────────

    public static function newWorkSubmitted(string $employeeName, string $leadTitle): void
    {
        self::sendToRole('ADMIN', 'new_work_submitted',
            '📝 New Work Submitted',
            "{$employeeName} submitted a new work entry: '{$leadTitle}'.",
            '/leads'
        );
        self::sendToRole('SUPER ADMIN', 'new_work_submitted',
            '📝 New Work Submitted',
            "{$employeeName} submitted a new work entry: '{$leadTitle}'.",
            '/leads'
        );
    }

    public static function leadMatured(string $leadTitle): void
    {
        self::sendToRole('ADMIN', 'lead_matured',
            '🔥 Lead Matured',
            "Lead '{$leadTitle}' has matured and is ready for conversion to a project.",
            '/leads'
        );
        self::sendToRole('SUPER ADMIN', 'lead_matured',
            '🔥 Lead Matured',
            "Lead '{$leadTitle}' has matured and is ready for conversion to a project.",
            '/leads'
        );
    }

    public static function newPayoutRequest(string $employeeName, float $amount, int $requestId): void
    {
        self::sendToRole('ADMIN', 'new_payout_request',
            '💸 New Payout Request',
            "{$employeeName} has requested a payout of " . number_format($amount, 2) . ". Request #{$requestId}.",
            '/payouts'
        );
        self::sendToRole('SUPER ADMIN', 'new_payout_request',
            '💸 New Payout Request',
            "{$employeeName} has requested a payout of " . number_format($amount, 2) . ". Request #{$requestId}.",
            '/payouts'
        );
    }

    public static function clientPaymentDue(string $clientName, string $projectName, float $amount): void
    {
        self::sendToRole('ADMIN', 'client_payment_due',
            '⏰ Client Payment Due',
            "Payment of " . number_format($amount, 2) . " is due from client '{$clientName}' for project '{$projectName}'.",
            '/invoices'
        );
    }
}
