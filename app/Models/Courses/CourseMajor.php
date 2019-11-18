<?php

namespace App\Models\Courses;

use App\Models\ElectiveCourses\CourseElective;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Schools\Major;
use App\Models\School;

class CourseMajor extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'course_id','major_id','school_id','major_name','course_name','course_code'
    ];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function major(){
        return $this->belongsTo(Major::class);
    }

    public function school(){
        return $this->belongsTo(School::class);
    }

}
