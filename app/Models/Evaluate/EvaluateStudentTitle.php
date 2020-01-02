<?php


namespace App\Models\Evaluate;

use Illuminate\Database\Eloquent\Model;

class EvaluateStudentTitle extends Model
{
    const STATUS_START = 1; // 开启
    const STATUS_CLOSE = 0; // 关闭

    protected $fillable = ['school_id', 'title', 'status'];

}
