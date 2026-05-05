<?php

namespace App\Listeners;

use App\Events\ClearanceSubmitted;
use App\Models\Department;
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
        $departments = Department::where('is_active', true)
            ->with('officer')
            ->get();
        
        foreach ($departments as $department) {
            if ($department->officer) {
                $this->notificationService->createDatabaseNotification(
                    $department->officer_id,
                    'New Clearance Request',
                    "New clearance request {$event->clearance->reference_no} from student {$event->clearance->student->full_name} requires your approval.",
                    'new_clearance'
                );
            }
        }
    }
}