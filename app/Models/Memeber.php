<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\TaskMember;

class Memeber extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email'
    ];
    public function taskmember(){
        return $this->hasOne(TaskMember::class,'memberId');
    }
}
