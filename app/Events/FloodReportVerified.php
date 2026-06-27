<?php

namespace App\Events;

use App\Models\FloodReport;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class FloodReportVerified implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $report;

    /**
     * Create a new event instance.
     */
    public function __construct(FloodReport $report)
    {
        $this->report = $report;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $kecamatanSlug = 'global';
        if ($this->report->kecamatan) {
            $kecamatanSlug = Str::slug($this->report->kecamatan);
        }

        return [
            new Channel('disaster.' . $kecamatanSlug),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'FloodReportVerified';
    }
}
