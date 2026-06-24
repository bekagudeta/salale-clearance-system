<?php

namespace App\Listeners;

use App\Events\ClearanceApproved;
use App\Events\ClearanceCompleted;
use App\Events\ClearanceRejected;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyStudent implements ShouldQueue
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handleApproved(ClearanceApproved $event)
    {
        $studentUser = $event->approval->request->student->user;
        
        $this->notificationService->createDatabaseNotification(
            $studentUser->id,
            'Clearance Approved',
            "Your clearance has been approved by {$event->approval->department->name}. Current status: {$event->approval->request->status}",
            'approval'
        );
        
        $this->notificationService->sendEmailNotification(
            $studentUser,
            'approved',
            [
                'clearance' => $event->approval->request,
                'department' => $event->approval->department,
            ]
        );
    }

    public function handleRejected(ClearanceRejected $event)
    {
        $studentUser = $event->approval->request->student->user;

        // An academic head's rejection returns the request for fixing rather
        // than killing it, so the student gets an actionable message.
        $isReturned = $event->approval->department->isAcademic();

        if ($isReturned) {
            $title = 'Clearance Returned';
            $message = "Your clearance was returned by {$event->approval->department->name}. Reason: {$event->approval->remarks}. Please fix the issue and resubmit.";
        } else {
            $title = 'Clearance Rejected';
            $message = "Your clearance was rejected by {$event->approval->department->name}. Reason: {$event->approval->remarks}";
        }

        $this->notificationService->createDatabaseNotification(
            $studentUser->id,
            $title,
            $message,
            'rejection'
        );

        $this->notificationService->sendEmailNotification(
            $studentUser,
            'rejected',
            [
                'clearance' => $event->approval->request,
                'department' => $event->approval->department,
                'reason' => $event->approval->remarks,
            ]
        );
    }

    public function handleCompleted(ClearanceCompleted $event)
    {
        $studentUser = $event->clearance->student->user;
        
        $this->notificationService->createDatabaseNotification(
            $studentUser->id,
            'Clearance Completed',
            "Your clearance request {$event->clearance->reference_no} has been completed. You can now download your certificate.",
            'completion'
        );
        
        $this->notificationService->sendEmailNotification(
            $studentUser,
            'completed',
            ['clearance' => $event->clearance]
        );
    }

    public function subscribe($events)
    {
        $events->listen(
            ClearanceApproved::class,
            [NotifyStudent::class, 'handleApproved']
        );
        
        $events->listen(
            ClearanceRejected::class,
            [NotifyStudent::class, 'handleRejected']
        );
        
        $events->listen(
            ClearanceCompleted::class,
            [NotifyStudent::class, 'handleCompleted']
        );
    }
}