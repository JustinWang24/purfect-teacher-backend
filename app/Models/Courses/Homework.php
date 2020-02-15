<?php

namespace App\Models\Courses;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    public $table = 'homeworks';

    protected $fillable = [
        'lecture_id',
        'year',
        'student_id',
        'content',
        'media_id',
        'comment',
        'score',
    ];

    public function student(){
        return $this->belongsTo(User::class,'student_id');
    }

    public function lecture(){
        return $this->belongsTo(Lecture::class);
    }
}