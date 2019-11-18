<?php

namespace App\Models\ElectiveCourses;

use Illuminate\Database\Eloquent\Model;

class ApplyCourseArrangement extends Model
{
    protected $fillable = [
        'apply_id', 'week', 'day_index', 'time_slot_id',
        'teacher_notes', 'building_id', 'building_name',
        'classroom_id', 'classroom_name'
    ];
}
