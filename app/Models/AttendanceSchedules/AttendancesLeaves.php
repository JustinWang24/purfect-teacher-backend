<?php


namespace App\Models\AttendanceSchedules;


use Illuminate\Database\Eloquent\Model;

class AttendancesLeaves extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'timetable_id', 'year', 'term', 'reason', 'date', 'status'
    ];

    const STATUS_UNCHECKED = 0;  // 未审核
    const STATUS_CONSENT   = 1;  // 同意
    const STATUS_REFUSE    = 2;  // 拒绝
}
