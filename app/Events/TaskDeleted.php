<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;

class TaskDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $projectId; 
    public $taskId;
    public $taskData;
    /**
     * Create a new event instance.
     */
    public function __construct(Task $task)
    {
        $this->projectId = $task->projectId;
        $this->taskId = $task->id;
        $this->taskData = $task->toArray();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('project.'. $this->projectId),
        ];
    }
    public function broadcastWith(): array
    {
        return [
            'task' => $this->taskData,
            'taskId' => $this->taskId,
            'projectId' => $this->projectId
        ];
    }
}
