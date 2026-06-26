<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Mail\ClearanceApprovedMail;
use App\Mail\ClearanceRejectedMail;
use App\Mail\ClearanceCompletedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create database notification
     */
    public function createDatabaseNotification($userId, $title, $message, $type = null)
    {
        try {
            return Notification::create([
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create notification: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Send email notification
     */
    public function sendEmailNotification($user, $type, $data)
    {
        try {
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
                default:
                    return false;
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Notify about approval
     */
    public function notifyApproval($user, $clearance, $department)
    {
        // Database notification
        $this->createDatabaseNotification(
            $user->id,
            'Clearance Approved ✓',
            "Your clearance request ({$clearance->reference_no}) has been approved by {$department->name}. Current status: {$clearance->status}",
            'approval'
        );
        
        // Email notification
        $this->sendEmailNotification($user, 'approved', [
            'clearance' => $clearance,
            'department' => $department,
        ]);
    }

    /**
     * Notify student that they must clear a department case before approval.
     */
    public function notifyCaseHold($user, $clearance, $department, $reason)
    {
        $this->createDatabaseNotification(
            $user->id,
            'Clearance On Hold — Action Required',
            "Your clearance request ({$clearance->reference_no}) is on hold at {$department->name}. Please clear your case before approval.\n\nOfficer comment: {$reason}",
            'case_hold'
        );
    }

    /**
     * Notify about rejection
     */
    public function notifyRejection($user, $clearance, $department, $reason)
    {
        // Database notification
        $this->createDatabaseNotification(
            $user->id,
            'Clearance Rejected ✗',
            "Your clearance request ({$clearance->reference_no}) was rejected by {$department->name}.\nReason: {$reason}",
            'rejection'
        );
        
        // Email notification
        $this->sendEmailNotification($user, 'rejected', [
            'clearance' => $clearance,
            'department' => $department,
            'reason' => $reason,
        ]);
    }

    /**
     * Notify about completion
     */
    public function notifyCompletion($user, $clearance)
    {
        // Database notification
        $this->createDatabaseNotification(
            $user->id,
            'Clearance Completed 🎉',
            "Congratulations! Your clearance request ({$clearance->reference_no}) has been completed. You can now download your certificate.",
            'completion'
        );
        
        // Email notification
        $this->sendEmailNotification($user, 'completed', [
            'clearance' => $clearance,
        ]);
    }

    /**
     * Send reminder to officer about pending approval
     */
    public function sendReminder($officer, $approval)
    {
        $this->createDatabaseNotification(
            $officer->id,
            'Pending Approval Reminder ⏰',
            "Clearance request {$approval->request->reference_no} has been pending for over 3 days. Please review and take action.",
            'reminder'
        );
    }

    /**
     * Send bulk notifications
     */
    public function sendBulkNotifications(array $userIds, $title, $message, $type = null)
    {
        $notifications = [];
        
        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        return Notification::insert($notifications);
    }

    /**
     * Get unread notifications count for user
     */
    public function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId, $userId)
    {
        return Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->update(['is_read' => true]);
    }

    /**
     * Mark all notifications as read for user
     */
    public function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Delete old notifications
     */
    public function deleteOldNotifications($days = 30)
    {
        $date = now()->subDays($days);
        return Notification::where('created_at', '<', $date)->delete();
    }
}