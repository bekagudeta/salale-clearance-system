<?php

namespace App\Console\Commands;

use App\Models\ClearanceApproval;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendReminderNotifications extends Command
{
    protected $signature = 'clearance:send-reminders';
    protected $description = 'Send reminders for pending approvals older than 3 days';

    public function handle(NotificationService $notificationService)
    {
        $pendingApprovals = ClearanceApproval::where('status', 'pending')
            ->where('created_at', '<', now()->subDays(3))
            ->with(['request.student.user', 'department.officer'])
            ->get();
        
        foreach ($pendingApprovals as $approval) {
            if ($approval->department->officer) {
                $notificationService->createDatabaseNotification(
                    $approval->department->officer_id,
                    'Pending Approval Reminder',
                    "Clearance request {$approval->request->reference_no} has been pending for over 3 days.",
                    'reminder'
                );
            }
        }
        
        $this->info('Reminder notifications sent successfully.');
    }
}