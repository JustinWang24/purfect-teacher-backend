<?php


namespace App\Models\Evaluate;

use Illuminate\Database\Eloquent\Model;

class EvaluateStudentRecord extends Model
{
    protected $fillable = ['teacher_id', 'user_id', 'evaluate_id', 'score', 'desc'];

}
