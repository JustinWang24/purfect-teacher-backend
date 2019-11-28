<?php

namespace App\Models\AttendanceSchedules;

use Illuminate\Database\Eloquent\Model;

class AttendanceTask extends Model
{
    protected $fillable = [
        'school_id', 'type', 'title',
        'content', 'start_time', 'end_time',
    ];

    public function timeSlots()
    {
        return $this->hasMany(AttendanceTimeSlot::class, 'task_id', 'id');
    }
    public function schedules()
    {
        return $this->hasMany(AttendanceSchedule::class, 'task_id', 'id');
    }
}
