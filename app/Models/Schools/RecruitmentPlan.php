<?php

namespace App\Models\Schools;

use App\Models\School;
use App\User;
use Illuminate\Database\Eloquent\Model;

class RecruitmentPlan extends Model
{
    const TYPE_GENERAL = 1; // 统招
    const TYPE_SELF = 2;    // 自主招生

    protected $fillable = [
        'school_id',
        'major_id',
        'major_name',// 专业名
        'type', // 招生类型: 自主招生/统招 等
        'title',// 本次招生计划的标题
        'start_at',  // 开始招生日期
        'end_at',    // 招生截止日期
        'expired',  // 强制结束招生
        'description',  // 招生简章详情
        'tease',  // 简介
        'tags',  // 标签
        'fee',  // 专业学费
        'hot',  // 热门专业
        'seats',// 招生人数
        'grades_count',// 招几个班级
        'year', // 招生年度
        'applied_count', // 已报名人数
        'enrolled_count', // 已招生人数
        'manager_id', // 负责人: 本次招生的收信人
    ];

    public function major(){
        return $this->belongsTo(Major::class);
    }

    public function school(){
        return $this->belongsTo(School::class);
    }

    public function manager(){
        return $this->belongsTo(User::class,'manager_id');
    }
}
