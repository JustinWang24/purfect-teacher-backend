<?php

namespace App\Models\TeacherAttendance;

use Illuminate\Database\Eloquent\Model;

class Clockin extends Model
{
    const TYPE_MORNING = 'morning';//早上
    const TYPE_AFTERNOON = 'afternoon';//下午
    const TYPE_EVENING = 'evening';//傍晚

    const STATUS_NONE = 0;
    const STATUS_NORMAL = 1;//正常
    const STATUS_LATE = 2;//迟到
    const STATUS_LATER = 3;//严重迟到
    const STATUS_EARLY = 4;//早退

    const SOURCE_APP = 1;//客户端打卡
    const SOURCE_APPLY = 2;//审批补卡

    //
    public $table = 'teacher_attendance_clockins';
    protected $fillable = [
        'teacher_attendance_id','user_id','day','time','type','status','source'
    ];
}
