<?php


namespace App\Models\Evaluate;


use Illuminate\Database\Eloquent\Model;

class EvaluateTeacherRecord extends Model
{

    protected $fillable = [
        'evaluate_teacher_id', 'evaluate_id', 'user_id', 'grade_id', 'score', 'desc'
    ];


}
