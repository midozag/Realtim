<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\TaskProgress;
use App\Models\Task;

class Project extends Model
{
    use HasFactory;
    const NOT_STARTED = 0;
    const PENDING = 1;
    const COMPLETED = 2;
    protected $fillable = [
        'name',
        'status',
        'startDate',
        'endDate',
        'slug'
    ];
    public static function createSlug($name){
        return Str::slug($name).Str::random(10).time();
    }
    public function taskProgress()
    {
        return $this->hasOne(TaskProgress::class,'projectId');
    }
    public function tasks(){
        return $this->hasMany(Task::class,'projectId');
    }
   
}
