<?php

namespace App\Listeners;

use App\Events\ClearanceSubmitted;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyDepartments implements ShouldQueue
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(ClearanceSubmitted $event)
    {
        // Stage one: only the student's academic department head/coordinator is
        // notified. Service departments are notified later, on ClearanceForwarded.
        $approval = $event->clearance->academicApproval();

        if (! $approval || ! $approval->department) {
            return;
        }

        $department = $approval->department->loadMissing('staff');

        foreach ($department->allStaff() as $person) {
            $this->notificationService->createDatabaseNotification(
                $person->id,
                'New Clearance Request',
                "New clearance request {$event->clearance->reference_no} from student {$event->clearance->student->full_name} requires your approval in {$department->name}.",
                'new_clearance'
            );
        }
    }
}