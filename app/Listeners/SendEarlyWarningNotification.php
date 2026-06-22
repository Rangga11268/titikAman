<?php

namespace App\Listeners;

use App\Events\TmaThresholdExceeded;
use App\Jobs\SendEarlyWarningNotificationJob;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEarlyWarningNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(TmaThresholdExceeded $event): void
    {
        // Dispatch the job to queue
        SendEarlyWarningNotificationJob::dispatch($event->waterGate, $event->newStatus);
    }
}
