<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Project;

class TaskProgress extends Model
{
    use HasFactory;
   protected $fillable = [
    'projectId',
    'pinned_on_dashboard',
    'progress'
   ];

   protected $casts = [
    'pinned_on_dashboard' => 'boolean',
    'progress' => 'integer'
   ];
    public function project()
   {
      return $this->belongsTo(Project::class,'projectId');
   }
}
