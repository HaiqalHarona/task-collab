<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// Guided by claude (first time using reverb)

class TaskMove implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $taskId;
    public $poolId;
    public $taskPos;
    public $projectId;

    public function __construct($taskId, $poolId, $taskPos, $projectId)
    {
        $this->taskId = $taskId;
        $this->poolId = $poolId;
        $this->taskPos = $taskPos;
        $this->projectId = $projectId;
    }

   // Using same presence channel defined in channel.php that returns user data
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('project.'.$this->projectId),
        ];
    }
}
