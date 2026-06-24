<?php

namespace App\Events;

use App\Models\ClearanceRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired when an academic head approves and the request opens up (stage two)
 * to the service departments.
 */
class ClearanceForwarded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $clearance;

    public function __construct(ClearanceRequest $clearance)
    {
        $this->clearance = $clearance;
    }
}
