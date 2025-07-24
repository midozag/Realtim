<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuhtController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TaskController;
use App\Models\Memeber;
use App\Models\TaskProgress;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuhtController::class)->group(function(){
   Route::post('/register',[AuhtController::class,'register']);
   Route::post('login',[AuhtController::class,'login']);
   Route::get('/validate/{id}/{token}',[AuhtController::class,'validate']);
   Route::get('auth/google',[AuhtController::class,'redirectToGoogle']);
   Route::get('/auth/google/callback',[AuhtController::class,'handleGoogleCallback']);
   Route::get('/getUser/{id}',[AuhtController::class,'getUser']);
   Route::get('/count',[ProjectController::class,'count']);
});
Route::controller(ProjectController::class)->group(function(){
    Route::post('/createProject',[ProjectController::class,'create']);
    Route::get('/projects',[ProjectController::class,'list']);
    Route::post('/searchProjects',[ProjectController::class,'search']);
    Route::post('/projectPinned/{id}',[ProjectController::class,'pinned']);
    Route::get('/getChartData',[ProjectController::class,'getProjectData']);
    Route::get('/getPinnedProject',[ProjectController::class,'getPinnedProject']);
    Route::get('/getProjectBySlug',[ProjectController::class,'getProjectBySlug']);
    Route::get('/getProjectById',[ProjectController::class,'getProjectById']);
});
Route::controller(MemberController::class)->group(function(){
    Route::post('/createMember',[MemberController::class,'create']);
    Route::get('/members',[MemberController::class,'list']);
    Route::post('/search',[MemberController::class,'search']);
    Route::post('/searchMember',[MemberController::class,'searchMember']);
    Route::get('allmembers',[MemberController::class,'listMembers']);
});
Route::controller(TaskController::class)->group(function(){
   Route::post('/createtask',[TaskController::class,'createTask']);
   Route::post('/deleteTask',[TaskController::class,'deleteTask']);
   Route::put('/updateProgress',[TaskController::class,'updateProgress']);
   Route::get('/getAllTasks',[TaskController::class,'listAllTask']);
   Route::get('/getTasks',[TaskController::class,'listTask']);
   Route::get('/getPendingTasks',[TaskController::class,'listPendingTask']);
   Route::get('/getCompletedTasks',[TaskController::class,'listCompletedTask']);   
   Route::post('/tasks/notstartedtopending',[TaskController::class,'NotStartedToPending']);
   Route::post('/tasks/notstartedtocompleted',[TaskController::class,'NotStartedToCompleted']);
   Route::post('/tasks/pendingtocompleted',[TaskController::class,'PendingToCompleted']);
   Route::post('/tasks/pendingtonotstarted',[TaskController::class,'PendingToNotstarted']);
   Route::post('/tasks/completedtopending',[TaskController::class,'CompletedToPending']);
   Route::post('/tasks/completedtonotstarted',[TaskController::class,'CompletedToNotstarted']);
});
