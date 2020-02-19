<?php

namespace App\Models\ElectiveCourses;

use App\Models\Course;
use App\User;
use Illuminate\Database\Eloquent\Model;

class StudentEnrolledOptionalCourse extends Model
{
    protected $fillable = [
        'course_id', 'teacher_id', 'user_id', 'status', 'school_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }


}
