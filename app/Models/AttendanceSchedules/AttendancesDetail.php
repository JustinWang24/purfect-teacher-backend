<?php


namespace App\Models\AttendanceSchedules;

use Illuminate\Database\Eloquent\Model;

class AttendancesDetail extends Model
{
    protected $fillable = ['attendance_id', 'course_id', 'timetable_id', 'student_id'];
}
