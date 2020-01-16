<?php


namespace App\Models\Evaluate;


use Illuminate\Database\Eloquent\Model;

class EvaluateStudent extends Model
{
    protected $fillable = ['evaluate_teacher_id', 'user_id', 'grade_id', 'score'];


    public function evaluateTeacher() {
        return $this->belongsTo(EvaluateTeacher::class);
    }
}
