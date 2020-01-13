<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/1/11
 * Time: 下午3:48
 */

namespace App\Models\OA;


use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectTaskMember extends Model
{
    const STATUS_UN_BEGIN = 1; // 未开始
    const STATUS_IN_PROGRESS = 2;   // 正在进行
    const STATUS_CLOSED = 3;   // 已结束
    const STATUS_OVERTIME = 4; //超时
    const STATUS_MY_CREATE = 4; // 自己发起的

    protected $fillable = ['user_id', 'task_id'];


    protected $table = 'oa_project_task_members';


    public function projectTask() {
        return $this->belongsTo(ProjectTask::class,'task_id');
    }


    public function user() {
        return $this->belongsTo(User::class);
    }

}