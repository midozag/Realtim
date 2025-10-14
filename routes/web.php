<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Events\MessageSent;
use App\Models\Task;
Route::get('/app', function () {
    return view('welcome');
});
Route::get('/app/{any}',function(){
    return view('welcome');
});

use App\Events\NewProjectCreated;

// Broadcasting authentication routes
Broadcast::routes(['prefix' => 'api', 'middleware' => ['api']]);

Route::get('/test-broadcast/{projectId}', function($projectId) {
    $task = Task::first(); // Get any task

    // Test all three events
    event(new \App\Events\TaskCreated($task));
    event(new \App\Events\TaskDeleted($task));
    event(new \App\Events\TaskStatusUpdated($projectId, $task));

    return 'Events dispatched - check your console!';
});

Route::get('/send-test-message', function() {
    $message = 'Test message sent at ' . now()->format('H:i:s');
    event(new MessageSent($message));
    return response()->json(['status' => 'Message sent!', 'message' => $message]);
});

