<?php

namespace App\Models\OA;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{

    const STATUS_UN_BEGIN = 1; // 未开始
    const STATUS_IN_PROGRESS = 2;   // 正在进行
    const STATUS_CLOSED = 3;   // 已结束
    const STATUS_OVERTIME = 4; //超时
    const STATUS_MY_CREATE = 4; // 自己发起的

    // 阅读状态
    const UN_READ = 0; // 未读
    const READ = 1; // 已读

    protected $table = 'oa_project_tasks';

    protected $hidden = ['updated_at'];
    public $fillable = [
        'status','project_id', 'user_id', 'title', 'content', 'is_open',
        'end_time', 'create_user', 'remark', 'school_id'];

    public $user_field = ['*'];
    const MAP_ARR = [
        'id'            =>  'taskid',
        'title'         =>  'task_title',
        'content'       =>  'task_content',
        'status'        =>  'doing_status',
        'create_user'   =>  'create_userid',
        'user_id'       =>  'leader_userid',
        'create_time'   =>  'created_at',
        'project_id'    =>  'projectid',
        '_todo'         =>  ['create_name'=>'createUserName','project_title'=>'project_title','leader_name'=>'leader_name']
    ];
    /**
     * 任务的讨论
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discussions(){
        return $this->hasMany(ProjectTaskDiscussion::class,'project_task_id')->orderBy('id','desc');
    }

    /**
     * 发布任务的用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class)->select($this->user_field);
    }

    /**
     * 关联的项目
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(){
        return $this->belongsTo(Project::class);
    }


    /**
     * 任务项目成员
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taskMembers() {
        return $this->hasMany(ProjectTaskMember::class,'task_id');
    }


    /**
     * 创建人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createUser(){
        return $this->belongsTo(User::class,'create_user', 'id');
    }


    /**
     * 项目日志
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taskLogs() {
        return $this->hasMany(ProjectTaskLog::class, 'task_id')->orderBy('created_at');
    }


    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    public function getEndTimeAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }
}
