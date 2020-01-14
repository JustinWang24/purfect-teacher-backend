<?php

namespace App\Models\OA;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectTaskDiscussion extends Model
{
    protected $table = 'oa_project_task_discussions';

    protected $fillable = ['project_task_id', 'user_id', 'content', 'reply_user_id'];

    protected $hidden = ['updated_at'];

    public $user_field = ['*'];

    /**
     * 发言的用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class)->select($this->user_field);
    }


    /**
     * 回复人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function replyUser() {
        return $this->belongsTo(User::class, 'reply_user_id')
            ->select($this->user_field);
    }
}
