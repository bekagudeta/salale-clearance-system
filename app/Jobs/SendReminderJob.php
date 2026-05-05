<?php

namespace App\Jobs;

use App\Models\ClearanceApproval;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $approvalId;

    public function __construct($approvalId)
    {
        $this->approvalId = $approvalId;
    }

    public function handle(NotificationService $notificationService)
    {
        $approval = ClearanceApproval::with(['request.student', 'department.officer'])
            ->findOrFail($this->approvalId);
        
        if ($approval->status === 'pending') {
            if ($approval->department->officer) {
                $notificationService->createDatabaseNotification(
                    $approval->department->officer_id,
                    'Pending Approval Reminder',
                    "Clearance request {$approval->request->reference_no} from student {$approval->request->student->full_name} is pending your approval.",
                    'reminder'
                );
            }
        }
    }
}