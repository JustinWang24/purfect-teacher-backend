<?php

namespace App\Models;

use App\Models\Courses\CourseArrangement;
use App\Models\Courses\CourseTextbook;
use App\Models\ElectiveCourses\CourseElective;
use App\Models\ElectiveCourses\StudentEnrolledOptionalCourse;
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

    public $hidden = ['deleted_at'];

    public $casts = [
        'optional' => 'boolean', // 是否选修课
    ];

    public function majors(){
        return $this->hasMany(CourseMajor::class)->select(DB::raw('major_id as id, major_name as name'));
    }

    public function teachers(){
        return $this->hasMany(CourseTeacher::class)->select(DB::raw('teacher_id as id, teacher_name as name'));
    }

    public function school(){
        return $this->belongsTo(School::class);
    }

    public function courseArrangements()
    {
        return $this->hasMany(CourseArrangement::class);
    }

    public function courseElective()
    {
        return $this->hasOne(CourseElective::class);
    }

    public function studentEnrolledOptionalCourse()
    {
        return $this->hasMany(StudentEnrolledOptionalCourse::class);
    }

    /**
     * 本课程的课程安排
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function arrangements(){
        return $this->hasMany(CourseArrangement::class)
            ->orderBy('week', 'asc')
            ->orderBy('day_index','asc');
    }



    /**
     * 课程和教材关联
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courseTextbooks() {
        return $this->hasMany(CourseTextbook::class);
    }
}
