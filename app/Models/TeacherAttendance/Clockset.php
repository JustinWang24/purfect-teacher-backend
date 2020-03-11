<?php

namespace App\Models\TeacherAttendance;

use Illuminate\Database\Eloquent\Model;

class Clockset extends Model
{
    //
    public $table = 'teacher_attendance_clocksets';
    protected $fillable = [
        'teacher_attendance_id','week','start','end','morning','morning_late','afternoon_start','afternoon','afternoon_late','evening','is_weekday'
    ];
}
