<?php

namespace App\Models;

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
        $task = Task::where('id',$taskId)
        ->update(([
            'status' => $status,
        ]));
    }
}
