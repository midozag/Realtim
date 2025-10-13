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

class TaskCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $projectId; 
    public $task;
    /**
     * Create a new event instance.
     */
    public function __construct(Task $task)
    {
        $this->projectId = $task->projectId;
        $this->task = $task;
        \Log::info('TaskCreated event constructed for project: ' . $this->projectId);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channel = 'project.' . $this->projectId;
        \Log::info('Broadcasting TaskCreated on channel: ' . $channel);
    
        return [
          new Channel($channel),
        ];
    }
    public function broadcastWith(): array
    {
        return [
            'task' => $this->task,
            'project' => $this->projectId
        ];
    }
}