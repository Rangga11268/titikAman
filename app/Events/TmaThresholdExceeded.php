<?php

namespace App\Events;

use App\Models\WaterGate;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TmaThresholdExceeded
{
    use Dispatchable, SerializesModels;

    public $waterGate;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(WaterGate $waterGate, string $oldStatus, string $newStatus)
    {
        $this->waterGate = $waterGate;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }
}
