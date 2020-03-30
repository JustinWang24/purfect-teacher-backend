<?php

namespace App\Models\OA;

use Illuminate\Database\Eloquent\Model;

class WorkLogRead extends Model
{
    protected $table = 'oa_teacher_work_log_reads';
    protected $fillable = ['user_id', 'work_id'];

}
