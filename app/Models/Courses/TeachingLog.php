<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Model;

class TeachingLog extends Model
{
    protected $fillable = [
        'course_id',
        'teacher_id',
        'title',
        'content',
    ];
}