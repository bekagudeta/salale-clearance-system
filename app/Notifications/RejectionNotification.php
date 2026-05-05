<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RejectionNotification extends Notification
{
    use Queueable;

    protected $approval;

    public function __construct($approval)
    {
        $this->approval = $approval;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Clearance Rejected',
            'message' => "Your clearance request was rejected by {$this->approval->department->name}. Reason: {$this->approval->remarks}",
            'clearance_id' => $this->approval->clearance_request_id,
            'department' => $this->approval->department->name,
            'reason' => $this->approval->remarks,
        ];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
                    ->subject('Clearance Rejected - Salale University')
                    ->line("Your clearance request has been rejected by {$this->approval->department->name}.")
                    ->line("Reason: {$this->approval->remarks}")
                    ->action('View Details', url("/student/clearance/{$this->approval->clearance_request_id}"))
                    ->line('Please contact the department for more information.');
    }
}