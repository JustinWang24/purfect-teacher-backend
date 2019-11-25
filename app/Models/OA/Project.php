<?php

namespace App\Models\OA;

use App\Models\School;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    const STATUS_IN_PROGRESS = 1;
    const STATUS_CLOSED = 2;

    protected $table = 'oa_projects';

    public $casts = [
        'created_at'=>'date'
    ];

    protected $fillable = [
        'user_id','school_id','title','content','status'
    ];

    public function members(){
        return $this->hasMany(ProjectMember::class);
    }

    public function school(){
        return $this->belongsTo(School::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tasks(){
        return $this->hasMany(ProjectTask::class)->orderBy('id','desc');
    }
}
