<?php

namespace App\Models\AttendanceSchedules;

use Illuminate\Database\Eloquent\Model;

class AttendanceSchedulePerson extends Model
{
    protected $table = 'attendance_schedule_persons';
    protected $fillable = [
        'task_id', 'schedule_id', 'user_id',
    ];
}
