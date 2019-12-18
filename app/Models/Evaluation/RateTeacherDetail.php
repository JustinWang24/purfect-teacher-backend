<?php

namespace App\Models\Evaluation;

use App\User;
use Illuminate\Database\Eloquent\Model;

class RateTeacherDetail extends Model
{
    protected $fillable = [
        'year',
        'term',
        'teacher_id',
        'student_id',
        'course_id',
        'timetable_item_id',
        'calendar_week_number',
        'average_points',
        'prepare',
        'material',
        'on_time',
        'positive',
        'result',
        'other',
        'comment',
    ];

    public function teacher(){
        return $this->belongsTo(User::class,'teacher_id');
    }

    public function student(){
        return $this->belongsTo(User::class,'student_id');
    }

    /**
     * @return int
     */
    public function totalPoints(){
        return $this->prepare + $this->material + $this->on_time + $this->positive + $this->result + $this->other;
    }
}
