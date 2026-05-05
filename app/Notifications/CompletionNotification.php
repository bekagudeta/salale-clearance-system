<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CompletionNotification extends Notification
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
            'title' => 'Clearance Completed',
            'message' => "Your clearance request {$this->clearance->reference_no} has been completed.",
            'clearance_id' => $this->clearance->id,
            'reference_no' => $this->clearance->reference_no,
        ];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
                    ->subject('Clearance Completed - Salale University')
                    ->line("Your clearance request {$this->clearance->reference_no} has been successfully completed.")
                    ->line("You can now download your clearance certificate.")
                    ->action('Download Certificate', url("/student/clearance/{$this->clearance->id}/download"))
                    ->line('Congratulations!');
    }
}