<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Events\MessageSent;
use App\Models\Task;
use App\Events\NewProjectCreated;

Route::get('/', function () {
    return redirect('/app');
});

Route::get('/app', function () {
    return view('welcome');
});

Route::get('/app/{any}', function(){
    return view('welcome');
})->where('any', '.*');

Broadcast::routes(['prefix' => 'api', 'middleware' => ['api']]);

Route::get('/test-broadcast/{projectId}', function($projectId) {
    $task = Task::first();
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
Route::get('/test', function() { return 'Test OK'; });
Route::get('/test2', function() { return view('test'); });
Route::get('/test2', function() { return view('test'); });
