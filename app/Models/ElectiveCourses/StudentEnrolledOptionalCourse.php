<?php

namespace App\Models\ElectiveCourses;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;

class StudentEnrolledOptionalCourse extends Model
{
    protected $fillable = [
        'course_id', 'teacher_id', 'user_id', 'status', 'school_id'
    ];

    public function user()
    {
        $this->belongsTo(Uses::class, 'id','user_id');
    }

    public function course()
    {
        $this->hasOne(Course::class, 'id', 'course_id');
    }


}
