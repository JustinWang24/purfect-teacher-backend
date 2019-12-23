<?php

namespace App\Models\OA;

use App\Models\School;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    const STATUS_IN_PROGRESS = 1;  // 正在进行
    const STATUS_CLOSED = 2;       // 已结束
    const MAP_ARR = [
        'id'=>'projectid',
        'title'=>'project_title',
        'content'=>'project_content',
        'created_at'=>'create_time',
        'user_id'=>'leader_userid',
        'status'=>'doing_status',
        '_todo' => ['member_count'=>'memberCount','leader_name'=>'username']
    ];

    protected $table = 'oa_projects';

    protected $hidden = ['updated_at'];

    public $casts = [
        'created_at'=>'date'
    ];

    protected $fillable = [
        'user_id','school_id','title','content','status', 'create_user', 'is_open'
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
    public function memberCount()
    {
        return $this->members()->count();
    }
    public function username()
    {
        return $this->user->name;
    }
}
