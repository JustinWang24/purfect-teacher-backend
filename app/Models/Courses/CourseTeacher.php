<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\User;
use App\Models\School;

class CourseTeacher extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'course_id','teacher_id','school_id','teacher_name','course_name','course_code'
    ];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function teacher(){
        return $this->belongsTo(User::class,'teacher_id');
    }

    public function school(){
        return $this->belongsTo(School::class);
    }
}
