<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProgressUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $progress;
    public $pendingTasks;
    public $completedTasks;
    public function __construct($progress,$pendingTasks,$completedTasks)
    {
        $this->progress = $progress;
        $this->pendingTasks = $pendingTasks;
        $this->completedTasks = $completedTasks;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('progress'),
        ];
    }
    public function broadcastWith(): array
    {
        return [
            'progress' => $this->progress,
            'pendingTasks' => $this->pendingTasks,
            'completedTasks' => $this->completedTasks
        ];
    }
}
