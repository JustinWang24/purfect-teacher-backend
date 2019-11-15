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

    public $simpleFields = ['id','name'];

    protected $fillable = [
        'course_id','major_id','school_id','major_name','course_name','course_code'
    ];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function courseSimple(){
        return $this->belongsTo(Course::class)->select($this->simpleFields);
    }

    public function electiveCourse(){
        return $this->belongsTo(Course::class)->where('optional');
    }

    public function major(){
        return $this->belongsTo(Major::class);
    }

    public function school(){
        return $this->belongsTo(School::class);
    }

    public function courseArrangement()
    {
        return $this->hasMany(CourseArrangement::class, 'course_id', 'course_id');
    }

    public function courseElective()
    {
        return $this->hasOne(CourseElective::class, 'course_id', 'course_id');
    }

    public function courseTeacher()
    {
        return $this->hasOne(CourseTeacher::class, 'course_id', 'course_id');
    }
}
