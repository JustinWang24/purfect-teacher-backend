<?php

namespace App\Models\AttendanceSchedules;

use Illuminate\Database\Eloquent\Model;

class AttendanceSchedule extends Model
{
    protected $fillable = [
        'attendance_id', 'user_id', 'start_time', 'end_time', 'time_slot_id', 'school_id', 'campus_id',
    ];
}
