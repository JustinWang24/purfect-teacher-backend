<?php

namespace App\Models\Courses;

use App\Models\Course;
use App\Models\NetworkDisk\Media;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LectureMaterial extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'lecture_id',
        'teacher_id',
        'course_id',
        'media_id',
        'type',
        'description',
        'url',
    ];

    public function lecture(){
        return $this->belongsTo(Lecture::class);
    }

    public function teacher(){
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function media(){
        return $this->belongsTo(Media::class);
    }
}