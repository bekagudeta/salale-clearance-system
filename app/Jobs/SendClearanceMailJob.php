<?php

namespace App\Jobs;

use App\Mail\ClearanceApprovedMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendClearanceMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $type;
    protected $data;

    public function __construct(User $user, $type, $data)
    {
        $this->user = $user;
        $this->type = $type;
        $this->data = $data;
    }

    public function handle()
    {
        switch ($this->type) {
            case 'approved':
                Mail::to($this->user->email)->send(new ClearanceApprovedMail($this->data));
                break;
            case 'rejected':
                Mail::to($this->user->email)->send(new ClearanceRejectedMail($this->data));
                break;
            case 'completed':
                Mail::to($this->user->email)->send(new ClearanceCompletedMail($this->data));
                break;
        }
    }
}