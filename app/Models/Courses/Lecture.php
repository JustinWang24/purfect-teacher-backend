<?php

namespace App\Models\Courses;

use App\Models\Course;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    protected $fillable = [
        'course_id',
        'teacher_id',
        'idx',
        'title',
        'summary',
        'tags',
    ];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function teacher(){
        return $this->belongsTo(User::class,'teacher_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lectureMaterials() {
        return $this->hasMany(LectureMaterial::class, 'lecture_id');
    }
}