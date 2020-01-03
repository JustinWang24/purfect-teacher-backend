<?php


namespace App\Models\Evaluate;


use Illuminate\Database\Eloquent\Model;

class EvaluateStudent extends Model
{
    protected $fillable = ['evaluate_teacher_id', 'user_id', 'grade_id', 'year', 'type',
        'status', 'desc', 'score'
    ];

    const STATUS_NOT_EVALUATE = 0; // 未评价
    const STATUS_EVALUATE = 1; // 已评价

    public function evaluateTeacher() {
        return $this->belongsTo(EvaluateTeacher::class);
    }
}
