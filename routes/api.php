<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuhtController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::controller(AuhtController::class)->group(function(){
   Route::post('/register',[AuhtController::class,'register']);
   Route::post('login',[AuhtController::class,'login']);
   Route::get('/validate/{id}/{token}',[AuhtController::class,'validate']);
});
