<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Events\MessageSent;

Route::get('/app', function () {
    return view('welcome');
});
Route::get('/app/{any}',function(){
    return view('welcome');
});

use App\Events\NewProjectCreated;

