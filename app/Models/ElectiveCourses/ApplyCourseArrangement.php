<?php

namespace App\Models\ElectiveCourses;

use Illuminate\Database\Eloquent\Model;

class ApplyCourseArrangement extends Model
{
    protected $fillable = [
        'course_id', 'week', 'day_index', 'time_slot_id', 'teacher_notes'
    ];
}
