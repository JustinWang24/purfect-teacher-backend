<?php

namespace App\Models\ElectiveCourses;

use Illuminate\Database\Eloquent\Model;
use App\Models\ElectiveCourses\TeacherApplyElectiveCoursesTimeSlot;



class TeacherApplyElectiveCourse extends Model
{
    protected $fields = [
        'school_id', 'teacher_id', 'teacher_name', 'major_id',
        'code', 'name', 'scores', 'year', 'term', 'desc', 'open_num',
        'status', 'reply_content'
    ];


    public function TimeSlot(){
        return $this->hasMany(TeacherApplyElectiveCoursesTimeSlot::class);
    }


}
