<?php

namespace App\Models\TeacherAttendance;

use Illuminate\Database\Eloquent\Model;

class ExceptionDay extends Model
{
    //
    public $table = 'teacher_attendance_exception_days';
    protected $fillable = [
        'teacher_attendance_id','type','day'
    ];
}
