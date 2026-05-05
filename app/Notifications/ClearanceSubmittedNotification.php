<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ClearanceSubmittedNotification extends Notification
{
    use Queueable;

    protected $clearance;

    public function __construct($clearance)
    {
        $this->clearance = $clearance;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Clearance Request',
            'message' => "Student {$this->clearance->student->full_name} has submitted a new clearance request.",
            'clearance_id' => $this->clearance->id,
            'reference_no' => $this->clearance->reference_no,
        ];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
                    ->subject('New Clearance Request - Salale University')
                    ->line("A new clearance request has been submitted.")
                    ->line("Reference: {$this->clearance->reference_no}")
                    ->line("Student: {$this->clearance->student->full_name}")
                    ->action('View Request', url("/department/approvals/{$this->clearance->id}"))
                    ->line('Please review and take action.');
    }
}