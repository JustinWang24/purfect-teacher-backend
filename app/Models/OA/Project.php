<?php

namespace App\Models\OA;

use App\Models\School;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    const OPEN = 1;
    const INTERIOR = 2;

    const STATUS_NOT_BEGIN = 0;
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


    /**
     * 成员
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members(){
        return $this->hasMany(ProjectMember::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school(){
        return $this->belongsTo(School::class);
    }

    /**
     * 负责人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * 创建人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createUser() {
        return $this->belongsTo(User::class,'create_user');
    }

    /**
     * 任务
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks(){
        return $this->hasMany(ProjectTask::class, 'project_id')->orderBy('end_time', 'desc');
    }


}
