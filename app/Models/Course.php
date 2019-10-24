<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Courses\CourseTeacher;
use App\Models\Courses\CourseMajor;
use Illuminate\Support\Facades\DB;

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
        return $this->hasMany(CourseMajor::class)->select(DB::raw('major_id as id, major_name as name'));
    }

    public function teachers(){
        return $this->hasMany(CourseTeacher::class)->select(DB::raw('teacher_id as id, teacher_name as name'));
    }
}