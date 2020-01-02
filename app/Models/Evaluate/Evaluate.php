<?php


namespace App\Models\Evaluate;


use Illuminate\Database\Eloquent\Model;

class Evaluate extends Model
{
    protected $fillable = [
        'school_id', 'title', 'score'
    ];

    const TYPE_TEACHER = 1; // 给老师评价
    const TYPE_STUDENT = 2; // 给学生评价

}
