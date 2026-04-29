<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PoolMove implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    // Sent as JSON data to the frontend over the websocket (First Time using guided by claude)
    public $projectId;
    public $poolId;
    public $poolPos;


    public function __construct($projectId, $poolId, $poolPos)
    {
        $this->projectId = $projectId;
        $this->poolId = $poolId;
        $this->poolPos = $poolPos;
    }


    /**
    *   Broadcast to the project presence channel for the project opened
    *   Using Broadcast::chanel('project.{projectId}') in channel.php
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('project.'.$this->projectId),
        ];
    }
}
