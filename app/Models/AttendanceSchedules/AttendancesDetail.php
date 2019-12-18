<?php


namespace App\Models\AttendanceSchedules;

use Illuminate\Database\Eloquent\Model;

class AttendancesDetail extends Model
{
    protected $fillable = ['attendance_id', 'course_id', 'timetable_id', 'student_id'];

    const TYPE_INTELLIGENCE = 1; // 云班牌签到
    const TYPE_MANUAL = 2;      // 手动签到
    const TYPE_SWEEP_CODE = 3; // 扫码补签


}
