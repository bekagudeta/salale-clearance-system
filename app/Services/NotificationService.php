<?php

namespace App\Services;

use App\Models\Notification;
use App\Mail\ClearanceApprovedMail;
use App\Mail\ClearanceRejectedMail;
use App\Mail\ClearanceCompletedMail;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function createDatabaseNotification($userId, $title, $message, $type = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]);
    }

    public function sendEmailNotification($user, $type, $data)
    {
        switch ($type) {
            case 'approved':
                Mail::to($user->email)->send(new ClearanceApprovedMail($data));
                break;
            case 'rejected':
                Mail::to($user->email)->send(new ClearanceRejectedMail($data));
                break;
            case 'completed':
                Mail::to($user->email)->send(new ClearanceCompletedMail($data));
                break;
        }
    }

    public function notifyApproval($user, $clearance, $department)
    {
        $this->createDatabaseNotification(
            $user->id,
            'Clearance Approved',
            "Your clearance has been approved by {$department->name} department.",
            'approval'
        );
    }

    public function notifyRejection($user, $clearance, $department, $reason)
    {
        $this->createDatabaseNotification(
            $user->id,
            'Clearance Rejected',
            "Your clearance was rejected by {$department->name}. Reason: {$reason}",
            'rejection'
        );
    }

    public function notifyCompletion($user, $clearance)
    {
        $this->createDatabaseNotification(
            $user->id,
            'Clearance Completed',
            "Your clearance request {$clearance->reference_no} has been completed. You can now download your certificate.",
            'completion'
        );
    }
}