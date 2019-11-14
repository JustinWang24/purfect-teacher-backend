<?php

namespace App\Models\ElectiveCourses;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TeacherApplyElectiveCoursesTimeSlot
 * @package App\Models\ElectiveCourses
 */
class TeacherApplyElectiveCoursesTimeSlot extends Model
{
    protected $fields = [
        'teacher_apply_elective_courses_id', 'week', 'day_index', 'time_slot_id'
    ];
    public function apply(){
        return $this->belongsTo(TeacherApplyElectiveCourse::class);
    }
}
