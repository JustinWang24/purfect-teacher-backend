<?php

namespace App\Models\ElectiveCourses;

use App\Models\Timetable\TimeSlot;
use Illuminate\Database\Eloquent\Model;

class ApplyCourseArrangement extends Model
{
    protected $fillable = [
        'apply_id', 'week', 'day_index', 'time_slot_id',
        'teacher_notes', 'building_id', 'building_name',
        'classroom_id', 'classroom_name',
    ];

    public function timeslot() {
        return $this->belongsTo(TimeSlot::class, 'time_slot_id');
    }
}
