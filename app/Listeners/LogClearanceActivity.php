<?php

namespace App\Listeners;

use App\Events\ClearanceApproved;
use App\Events\ClearanceCompleted;
use App\Events\ClearanceRejected;
use App\Events\ClearanceSubmitted;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Request;

class LogClearanceActivity implements ShouldQueue
{
    public function handleSubmitted(ClearanceSubmitted $event)
    {
        ActivityLog::create([
            'user_id' => $event->clearance->student->user_id,
            'action' => 'submitted',
            'table_name' => 'clearance_requests',
            'record_id' => $event->clearance->id,
            'description' => "Submitted new clearance request: {$event->clearance->reference_no}",
            'ip_address' => Request::ip(),
        ]);
    }

    public function handleApproved(ClearanceApproved $event)
    {
        ActivityLog::create([
            'user_id' => $event->approval->approved_by,
            'action' => 'approved',
            'table_name' => 'clearance_approvals',
            'record_id' => $event->approval->id,
            'description' => "Approved clearance for department: {$event->approval->department->name}",
            'ip_address' => Request::ip(),
        ]);
    }

    public function handleRejected(ClearanceRejected $event)
    {
        ActivityLog::create([
            'user_id' => $event->approval->approved_by,
            'action' => 'rejected',
            'table_name' => 'clearance_approvals',
            'record_id' => $event->approval->id,
            'description' => "Rejected clearance for department: {$event->approval->department->name}. Reason: {$event->approval->remarks}",
            'ip_address' => Request::ip(),
        ]);
    }

    public function handleCompleted(ClearanceCompleted $event)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'completed',
            'table_name' => 'clearance_requests',
            'record_id' => $event->clearance->id,
            'description' => "Completed clearance request: {$event->clearance->reference_no}",
            'ip_address' => Request::ip(),
        ]);
    }

    public function subscribe($events)
    {
        $events->listen(
            ClearanceSubmitted::class,
            [LogClearanceActivity::class, 'handleSubmitted']
        );
        
        $events->listen(
            ClearanceApproved::class,
            [LogClearanceActivity::class, 'handleApproved']
        );
        
        $events->listen(
            ClearanceRejected::class,
            [LogClearanceActivity::class, 'handleRejected']
        );
        
        $events->listen(
            ClearanceCompleted::class,
            [LogClearanceActivity::class, 'handleCompleted']
        );
    }
}