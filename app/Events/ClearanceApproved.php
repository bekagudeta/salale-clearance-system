<?php

namespace App\Events;

use App\Models\ClearanceApproval;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClearanceApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $approval;

    public function __construct(ClearanceApproval $approval)
    {
        $this->approval = $approval;
    }
}