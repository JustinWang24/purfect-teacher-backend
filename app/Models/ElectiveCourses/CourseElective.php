<?php

namespace App\Models\ElectiveCourses;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;

class CourseElective extends Model
{
    protected $fillable = [
        'course_id', 'open_num', 'room_id'
    ];

    public function course(){
        return $this->belongsTo(Course::class);
    }
}
