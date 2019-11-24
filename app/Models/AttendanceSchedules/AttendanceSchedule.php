<?php

namespace App\Models\AttendanceSchedules;

use Illuminate\Database\Eloquent\Model;

class AttendanceSchedule extends Model
{
    protected $fillable = [
        'task_id', 'user_id', 'start_date_time', 'end_date_time', 'time_slot_id', 'school_id',
    ];

    public function person()
    {
        return $this->hasMany(AttendanceSchedulePerson::class,'task_id', 'id');
    }
}
