<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuhtController;
use App\Http\Controllers\ProjectController;

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
});
Route::controller(ProjectController::class)->group(function(){
    Route::post('/createProject',[ProjectController::class,'create']);
    Route::get('/projects',[ProjectController::class,'list']);
    Route::post('/searchProjects',[ProjectController::class,'search']);
});
