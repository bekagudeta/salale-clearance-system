<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApprovalNotification extends Notification
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
            'title' => 'Clearance Approved',
            'message' => "Your clearance request has been approved by {$this->approval->department->name}.",
            'clearance_id' => $this->approval->clearance_request_id,
            'department' => $this->approval->department->name,
        ];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
                    ->subject('Clearance Approved - Salale University')
                    ->line("Your clearance request has been approved by {$this->approval->department->name}.")
                    ->line("Current status: {$this->approval->request->status}")
                    ->action('Track Progress', url("/student/clearance/{$this->approval->clearance_request_id}"))
                    ->line('Thank you for using our service.');
    }
}