<?php

namespace App\Events;

use App\Models\SosRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SosDispatched implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sosRequest;

    /**
     * Create a new event instance.
     *
     * @param SosRequest $sosRequest
     */
    public function __construct(SosRequest $sosRequest)
    {
        $this->sosRequest = $sosRequest->load('user');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $kecamatanSlug = 'global';
        if ($this->sosRequest->user && $this->sosRequest->user->kecamatan) {
            $kecamatanSlug = Str::slug($this->sosRequest->user->kecamatan);
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
        return 'SosDispatched';
    }
}
