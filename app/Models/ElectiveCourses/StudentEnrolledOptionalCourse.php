<?php

namespace App\Models\ElectiveCourses;

use Illuminate\Database\Eloquent\Model;

class StudentEnrolledOptionalCourse extends Model
{
    protected $fillable = [
        'course_id', 'teacher_id', 'user_id', 'status'
    ];
}
