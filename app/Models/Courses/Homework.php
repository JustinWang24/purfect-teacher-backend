<?php

namespace App\Models\Courses;

use App\Models\Course;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    public $table = 'homeworks';

    protected $fillable = [
        'lecture_id',
        'course_id',
        'idx',
        'year',
        'student_id',
        'student_name',
        'content',
        'url',
        'comment',
        'score',
    ];

    public function student(){
        return $this->belongsTo(User::class,'student_id');
    }

    public function lecture(){
        return $this->belongsTo(Lecture::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function getUrlAttribute($val){
        return asset($val);
    }
}