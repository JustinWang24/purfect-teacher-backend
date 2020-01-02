<?php


namespace App\Models\Evaluate;


use Illuminate\Database\Eloquent\Model;

class EvaluateStudent extends Model
{
    protected $fillable = ['evaluate_teacher_id', 'user_id', 'grade_id'];

}
