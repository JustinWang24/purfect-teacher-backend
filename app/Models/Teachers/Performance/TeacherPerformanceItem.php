<?php

namespace App\Models\Teachers\Performance;

use Illuminate\Database\Eloquent\Model;

class TeacherPerformanceItem extends Model
{
    protected $fillable = [
        'school_id',
        'teacher_performance_id',
        'teacher_performance_config_id',
        'result', // 分项考核结果
        'comments', // 分项考核评语
    ];

    public $timestamps = false;

    protected function performance(){
        return $this->belongsTo(TeacherPerformance::class);
    }

    protected function config(){
        return $this->belongsTo(TeacherPerformanceConfig::class);
    }
}