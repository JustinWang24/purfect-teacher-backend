<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/1/13
 * Time: 上午9:09
 */

namespace App\Models\OA;


use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectTaskLog extends Model
{
    protected $fillable = ['school_id', 'user_id', 'task_id', 'desc'];

    protected $table = 'oa_project_task_logs';

    public $updated_at = false;


    public function user() {
        return $this->belongsTo(User::class);
    }
}