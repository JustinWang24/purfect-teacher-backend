<?php

namespace App\Models\TeacherAttendance;

use Illuminate\Database\Eloquent\Model;

class LeaveDetail extends Model
{
    //
    public $table = 'teacher_attendance_leave_details';
    protected $fillable = [
        'leave_id','teacher_attendance_id', 'user_id', 'source', 'day', 'time', 'type'
    ];
}
