<?php

namespace App\Models\AttendanceSchedules;

use Illuminate\Database\Eloquent\Model;

class AttendanceTimeSlot extends Model
{
    protected $fillable = [
        'task_id', 'title', 'start_time', 'end_time',
    ];
}
