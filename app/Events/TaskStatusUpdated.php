<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $projectId;
    public $taskData;
    /**
     * Create a new event instance.
     */
    public function __construct($projectId,$taskData)
    {
        $this->projectId=$projectId;
        $this->taskData=$taskData;

         \Log::info('TaskStatusUpdated Event Constructor Called', [
            'projectId' => $projectId,
            'taskData' => $taskData,
            'channel' => 'project.' . $projectId
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        \Log::info('TaskStatusUpdated broadcastOn called', [
            'channel' => 'project.' . $this->projectId
        ]);
        return [
            new Channel('project.'. $this->projectId),
        ];
    }
    public function broadcastWith(): array
    {
        \Log::info('TaskStatusUpdated broadcastWith called', [
            'data' => [
                'projectId' => $this->projectId,
                'taskData' => $this->taskData
            ]
        ]);
        return [
          'projectId' => $this->projectId,
          'taskData' => $this->taskData
        ];
    }
}