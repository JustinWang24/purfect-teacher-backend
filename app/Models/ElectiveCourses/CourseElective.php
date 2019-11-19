<?php

namespace App\Models\ElectiveCourses;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;

class CourseElective extends Model
{
    protected $fillable = [
        'course_id', 'open_num', 'room_id', 'max_num', 'enrol_start_at', 'expired_at'
    ];

    public function course(){
        return $this->belongsTo(Course::class);
    }
}
