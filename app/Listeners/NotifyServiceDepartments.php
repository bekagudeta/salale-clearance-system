<?php

namespace App\Listeners;

use App\Events\ClearanceForwarded;
use App\Models\Department;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Stage two: once the academic head approves, notify all active service
 * departments that the request now needs their approval.
 */
class NotifyServiceDepartments implements ShouldQueue
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(ClearanceForwarded $event)
    {
        $departments = Department::service()
            ->where('is_active', true)
            ->with('staff')
            ->get();

        foreach ($departments as $department) {
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
}
