<?php

namespace App\Models\TeacherAttendance;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    //
    const SOURCE_LEAVE = 1;
    const SOURCE_AWAY = 2;
    const SOURCE_TRAVEL = 3;
    public $table = 'teacher_attendance_leaves';
    protected $fillable = [
        'user_id','start', 'end', 'source', 'daybumber', 'teacher_attendance_id'
    ];
}
