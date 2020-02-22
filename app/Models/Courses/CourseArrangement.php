<?php

namespace App\Models\Courses;

use App\Models\Timetable\TimeSlot;
use Illuminate\Database\Eloquent\Model;

class CourseArrangement extends Model
{
    protected $fillable = [
        'course_id', 'week', 'day_index', 'time_slot_id',
        'building_id', 'building_name', 'classroom_id',
        'classroom_name'
    ];

    public function timeslot() {
        return $this->belongsTo(TimeSlot::class, 'time_slot_id');
    }
}
