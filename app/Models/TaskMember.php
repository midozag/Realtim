<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Memeber;

class TaskMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'projectId',
        'taskId',
        'memberId'
    ];
    public function member(){
        return $this->belongsTo(Memeber::class,'memberId');
    }
}
