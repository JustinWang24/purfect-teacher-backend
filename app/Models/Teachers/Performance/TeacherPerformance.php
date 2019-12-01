<?php

namespace App\Models\Teachers\Performance;

use Illuminate\Database\Eloquent\Model;

class TeacherPerformance extends Model
{
    const RESULT_A = 40;
    const RESULT_B = 30;
    const RESULT_C = 20;
    const RESULT_D = 10;

    const RESULT_A_TXT = '优秀';
    const RESULT_B_TXT = '良好';
    const RESULT_C_TXT = '一般';
    const RESULT_D_TXT = '不合格';

    protected $fillable = [
        'school_id',
        'user_id',
        'year',
        'result',
        'comments',
        'approved_by',
    ];

    public function Results(){
        return [
            self::RESULT_A=>self::RESULT_A_TXT,
            self::RESULT_B=>self::RESULT_B_TXT,
            self::RESULT_C=>self::RESULT_C_TXT,
            self::RESULT_D=>self::RESULT_D_TXT,
        ];
    }

    public function getResultText(){
        return self::Results()[$this->result];
    }

    public function items(){
        return $this->hasMany(TeacherPerformanceItem::class);
    }
}