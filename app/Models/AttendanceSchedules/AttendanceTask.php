<?php

namespace App\Models\AttendanceSchedules;

use Illuminate\Database\Eloquent\Model;

class AttendanceTask extends Model
{
    protected $fillable = [
        'school_id', 'campus_id', 'type', 'title',
        'content', 'start_time', 'end_time',
    ];

    public function timeSlots()
    {
        $this->hasMany(AttendanceTimeSlot::class);
    }
}
