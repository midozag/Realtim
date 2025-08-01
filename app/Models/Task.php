<?php

namespace App\Models;

use App\Events\TaskStatusUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\TaskMember;

class Task extends Model

{
    use HasFactory; 

    const NOT_STARTED = 0;
    const PENDING = 1;
    const COMPLETED = 2; 

    protected $fillable = [
        'projectId',
        'name',
        'status'
    ];
    public function taskmembers(){
        return $this->hasMany(TaskMember::class,'taskId');
    }
    public static function changeStatus($taskId,$status){
        $task = Task::find($taskId);
        if($task){
          $oldStatus = $task->status;
          $task->update([
            'status' => $status
          ]);
        
        broadcast(new TaskStatusUpdated($task->projectId,
        [
         'taskId' => $taskId,
         'oldStatus' => $oldStatus,
         'newStatus' => $status,
         'projectId' => $task->projectId 
        ]));
        return $task;
       }
       return false;
    }
}
