<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Project;
use App\Models\TaskProgress;
use App\Models\Task;
use App\Events\NewProjectCreated;

class ProjectController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(),[
         'name' => 'required',
         'startDate' => 'required',
         'endDate' => 'required',
        ]);
        if($validator->fails()){
         return response()->json([
             'status' => false,
             'message' => 'Validation errors',
             'errors' => $validator->errors()
         ],422);
        }
        $result =  DB::transaction(function() use ($request){
            $project = Project::create([
                'name' => $request->name,
                'status' => Project::NOT_STARTED,
                'startDate' => $request->startDate,
                'endDate' => $request->endDate,
                'slug' => Project::createSlug($request->name),
               ]);
            $taskprogress = TaskProgress::create([
                 'projectId' => $project->id,
                 'pinned_on_dashboard' => false,
                 'progress' => 0
               ]);
            return [
                'project' => $project,
                'taskprogress' => $taskprogress
            ];
        });  
        $count = Project::count();
        NewProjectCreated::dispatch($count);
        return response()->json([
            'status' => true,
            'message' => 'Project created succesfully with initial task progress',
            'project' => $result['project'],
            'taskprogress' => $result['taskprogress']
        ]);
    }
    public function list(Request $request){
        $per_page=$request->input('per_page');
        $projects = Project::with(['taskProgress'])->paginate($per_page);
        if(!$projects){
        return response()->json([
                'status' => false,
                'message' => 'Projects are not filtered',           
        ],505);    
        }
        return response()->json([
                'status' => true,
                'message' => 'Projects are filtered',
                'projects' => $projects
        ]);
    }
    public function search(Request $request){
        $perPage = $request->input('per_page');
        $page = $request->input('page');
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
           ]);
           if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ],422);
           }
        $projects = Project::where('name','LIKE',"%{$request->name}%")->orderBy('created_at','desc')->paginate($perPage);
        if($projects->isEmpty()){
            return response()->json([
                'status' => false,
                'message' => "No projects '{$request->name}' matching",
                'projects' => Project::with(['taskProgress'])->get(),
                'pagination' =>[
                    'total' => 0,
                    'per_page' => $perPage,
                    'current_page' => $page,
                    'last_page' => 0,
                    'from' => 0,
                    'to' => 0
                ]
                
              ],404);
           }
        return response()->json([
                'status' => true,
                'message' => "Projects returned succesfully",
                'projects' => $projects->items(), 
                'pagination' => [
                    'total' => $projects->total(),
                    'per_page' => $projects->perPage(),
                    'current_page' => $projects->currentPage(),
                    'last_page' => $projects->lastPage(),
                    'from' => $projects->firstItem(),
                    'to' => $projects->lastItem(),
                ]]);
    }
    public function count(Request $request){
        $count = Project::count();
        return response()->json([
            'count' => $count
        ]);
    }
    public function pinned(Request $request,$id){
        if(!is_numeric($id)){
            return response()->json([
                'status' => false,
                'message' => 'Invalid id'
            ]);
        };
        TaskProgress::where('pinned_on_dashboard',true)
        ->update([
           'pinned_on_dashboard' => false
        ]);
        $taskprogress = TaskProgress::where('projectId',$id);
        $taskprogress->update([
            'pinned_on_dashboard' => true
        ]); 
        return response()->json([
            'status' => true,
            'message' => 'Project '.$id.'is pinned'
        ]);
    }
    public function getPinnedProject(Request $request){
        
        $pinnedProject = TaskProgress::where('pinned_on_dashboard',1)->first();
        
        
        return response([
            'status' => true,
            'project' => $pinnedProject->projectId,
            'message' => 'Pinned projects'
        ]);
    }
    public function getProjectData(Request $request){

      $projectId = $request->projectId; 
      $task = Task::where('projectId',$projectId)->get();
      $taskprogress = TaskProgress::where('projectId',$projectId)
       ->select('progress')
       ->first();
      $pending = 0;
      $completed = 0;
      foreach($task as $row){
        if($row->status == Task::PENDING){
          $pending++;
        }
        if($row->status == Task::COMPLETED){
          $completed++;
        }
      }
      return response([
        'tasks' => [$pending,$completed],
        'progress' => $taskprogress->progress
       ]);
    }
     public function getProject(Request $request){
        $slug = $request->slug;
        $projects = Project::with(['tasks.taskmembers.member','taskProgress'])
        ->where('projects.slug',$slug)->first();
        if(!$slug){
          return response()->json([
            'status' => false,
            'message' => 'no projects',
        ]);   
        }
        return response()->json([
            'status' => true,
            'message' => 'Projects got succesfully',
            'projects' => $projects
        ]);
    }
}
