<?php

use Illuminate\Support\Facades\Route;

Route::get('/app', function () {
    return view('welcome');
});
Route::get('/app/{any}',function(){
    return view('welcome');
});

