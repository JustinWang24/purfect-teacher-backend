<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/1/11
 * Time: 下午3:48
 */

namespace App\Models\OA;


use Illuminate\Database\Eloquent\Model;

class ProjectTaskMember extends Model
{
    protected $fillable = ['user_id', 'task_id'];


    protected $table = 'oa_project_task_members';


    public function projectTask() {
        return $this->belongsTo(ProjectTask::class,'task_id');
    }

}