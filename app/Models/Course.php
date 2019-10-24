<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Courses\CourseTeacher;
use App\Models\Courses\CourseMajor;

class Course extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'code','name','uuid',
        'scores',
        'optional',
        'year',
        'term',
        'desc','school_id'
    ];

    public function majors(){
        return $this->hasMany(CourseMajor::class);
    }

    public function teachers(){
        return $this->hasMany(CourseTeacher::class);
    }
}