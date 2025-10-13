<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskMember;
use App\Models\TaskProgress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskStatusUpdated;

class TaskController extends Controller
{
    public function createTask(Request $request){
                $validator = Validator::make($request->all(),[
            'name' => 'required',
            'projectId' => 'required|numeric',
            'memberIds' => 'required|array',
            'memberIds.*' => 'numeric',
           ]);
           if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ],422);
           }
        $result = DB::transaction(function () use($request){
            $task = Task::create([
                'name' => $request->name,
                'projectId' => $request->projectId,
                'status' => Task::NOT_STARTED,
            ]);
            $members = $request->memberIds;
            for($i=0;$i<count($members);$i++){
                TaskMember::create([
                    'projectId' => $request->projectId,
                    'taskId' => $task->id,
                    'memberId' => $members[$i]
                ]);
            }
            event(new TaskCreated($task));
            return [
                'task' => $task
            ];
        });
        return response()->json([
            'status' => true,
            'message' => 'Task added succesfully',
            'task' => $result['task']
        ]);
    }
    
    public function listAllTask(Request $request){
        $projectId = $request->projectId;
        $tasks = Task::with('taskmembers.member')
        ->where('projectId',$projectId)
        ->get();
        if(count($tasks) === 0){
            return response()->json([
                'status' => false,
                'message' => 'No tasks existed',
            ],422);
        }
        return response()->json([
            'status' => true,
            'message' => 'Tasks returned succesfully',
            'tasks' => $tasks
        ]);
    }
    public function listTask(Request $request){
        $projectId = $request->projectId;
        $tasks = Task::with('taskmembers.member')
        ->where('projectId',$projectId)
        ->where('status',0)
        ->get();
        if(count($tasks) === 0){
            return response()->json([
                'status' => false,
                'message' => 'No Started tasks existed',
            ],422);
        }
        return response()->json([
            'status' => true,
            'message' => 'Tasks returned succesfully',
            'tasks' => $tasks
        ]);
    }
    public function listPendingTask(Request $request){
        $projectId = $request->projectId;
        $tasks = Task::with('taskmembers.member')
        ->where('projectId',$projectId)
        ->where('status',1)
        ->get();
        if(count($tasks) === 0){
            return response()->json([
                'status' => false,
                'message' => 'No Pending tasks existed',
            ],422);
        }
        return response()->json([
            'status' => true,
            'message' => 'Pending Tasks returned succesfully',
            'tasks' => $tasks
        ]);
    }
    public function listCompletedTask(Request $request){
        $projectId = $request->projectId;
        $tasks = Task::with('taskmembers.member')
        ->where('projectId',$projectId)
        ->where('status',2)
        ->get();
        if(count($tasks) === 0){
            return response()->json([
                'status' => false,
                'message' => 'No Completed tasks existed',
            ],422);
        }
        return response()->json([
            'status' => true,
            'message' => 'Completed Tasks returned succesfully',
            'tasks' => $tasks
        ]);
    }
    public function deleteTask(Request $request){
       $validator = Validator::make($request->all(),[
            'id' => 'required|numeric',
           ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ],422);}
       $task = Task::with('taskmembers.member')->findOrFail($request->id);
       if(!$task){
        return response()->json([
            'status' => false,
            'message' => 'No task exist'
        ],404);}
        
        // Create a copy of task data before deletion
        $taskData = $task->toArray();
        
        TaskDeleted::dispatch($task);
        $task->delete();
        return response()->json([
            'status' => true,
            'message' => 'task deleted',
        ]);
     
    }
 
    public function NotStartedToPending(Request $request){
        $task = Task::where('id',$request->taskid)->first();
        if(!$task){
          return response()->json([
            'status' => false,
            'message' => 'No tasks found'
        ],422);  
        }
        Task::changeStatus($request->taskid,Task::PENDING);
        $task = Task::find($request->taskid);
        TaskStatusUpdated::dispatch($task->projectId, $task);
        return response()->json([
            'status' => true,
            'message' => 'Task updated succesfully to pending'
        ]);
    }
    public function NotStartedToCompleted(Request $request){
        Task::changeStatus($request->taskid,Task::COMPLETED);
        $task = Task::find($request->taskid);
        TaskStatusUpdated::dispatch($task->projectId, $task);
        return response()->json([
            'status' => true,
            'message' => 'Task updated succesfully to completed'
        ]);
    }
    public function PendingToCompleted(Request $request){
        Task::changeStatus($request->taskid,Task::COMPLETED);
        $task = Task::find($request->taskid);
        TaskStatusUpdated::dispatch($task->projectId, $task);
        return response()->json([
            'status' => true,
            'message' => 'Task updated succesfully to completed'
        ]);
        
    }
    public function PendingToNotstarted(Request $request){
        Task::changeStatus($request->taskid,Task::NOT_STARTED);
        $task = Task::find($request->taskid);
        TaskStatusUpdated::dispatch($task->projectId, $task);
        return response()->json([
            'status' => true,
            'message' => 'Task updated succesfully to not_started'
        ]);
    }
    public function CompletedToPending(Request $request){
        Task::changeStatus($request->taskid,Task::PENDING);
        $task = Task::find($request->taskid);
        TaskStatusUpdated::dispatch($task->projectId, $task);
        return response()->json([
            'status' => true,
            'message' => 'Task updated succesfully to pending'
        ]);
    }
    public function CompletedToNotstarted(Request $request){
        Task::changeStatus($request->taskid,Task::NOT_STARTED);
        $task = Task::find($request->taskid);
        TaskStatusUpdated::dispatch($task->projectId, $task);
        return response()->json([
            'status' => true,
            'message' => 'Task updated succesfully to not_started'
        ]);
    }
    public function updateProgress(Request $request){
        $validator = Validator::make($request->all(),[
            'progress' => 'required|numeric',
            'id' => 'required|numeric'
           ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ],422);}
        $task_progress = TaskProgress::where('projectId',$request->id)->first();
        $task_progress->update([
            'progress' => $request->progress
        ]);
        if(!$task_progress){
        return response()->json([
            'status' => false,
            'message' => 'No task exist'
        ],404);}
        return response()->json([
            'status' => true,
            'message' => 'progress updated',
        ]);
    }
}
