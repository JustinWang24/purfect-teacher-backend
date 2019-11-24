<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Model;

class CourseArrangement extends Model
{
    protected $fillable = [
        'course_id', 'week', 'day_index', 'time_slot_id',
        'building_id', 'building_name', 'classroom_id',
        'classroom_name'
    ];
}
