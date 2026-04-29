<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BoardUpdates implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // Board Auto Update mainly for creation and delete of the tasks and pools
    public $projectId;
    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('project.'.$this->projectId),
        ];
    }
}
