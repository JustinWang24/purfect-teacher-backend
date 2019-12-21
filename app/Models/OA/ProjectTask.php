<?php

namespace App\Models\OA;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    const STATUS_IN_PROGRESS = 1;   // 正在进行
    const STATUS_CLOSED = 2;   // 已结束
    protected $table = 'oa_project_tasks';

    protected $hidden = ['updated_at'];
    public $fillable = ['project_id', 'user_id', 'title', 'content', 'is_open', 'end_time', 'create_user', 'remark'];

    public $user_field = ['*'];
    const MAP_ARR = [
        'id'=>'taskid',
        'title'=>'task_title',
        'content'=>'project_content',
        'status'=>'doing_status',
    ];
    /**
     * 任务的讨论
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discussions(){
        return $this->hasMany(ProjectTaskDiscussion::class)->orderBy('id','desc');
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
}
