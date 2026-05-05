<?php

namespace App\Events;

use App\Models\ClearanceRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClearanceCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $clearance;

    public function __construct(ClearanceRequest $clearance)
    {
        $this->clearance = $clearance;
    }
}