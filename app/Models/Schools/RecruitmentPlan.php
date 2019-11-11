<?php

namespace App\Models\Schools;

use App\Models\School;
use App\User;
use App\Utils\Time\GradeAndYearUtil;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Courses\CourseMajor;

class RecruitmentPlan extends Model
{
    use SoftDeletes;

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
        'manager_id', // 报名表审核负责人: 本次招生的收信人
        'enrol_manager', // 录取负责人: 本次录取工作的收信人
        'target_students', // 录取方式
        'student_requirements', // 报名条件
        'how_to_enrol', // 录取方式
    ];

    public $dates = [
        'start_at','end_at',
    ];

    protected $casts = [
        'start_at' => 'datetime:Y-m-d',
        'end_at' => 'datetime:Y-m-d',
        'hot'=>'boolean',
    ];

    public function setStartAtAttribute($value)
    {
        $this->attributes['start_at'] = GradeAndYearUtil::ConvertJsTimeToCarbon($value);
    }

    /**
     * @param $value
     */
    public function setEndAtAttribute($value)
    {
        if(empty($value)){
            $this->attributes['end_at'] = null;
        }
        else{
            $this->attributes['end_at'] = GradeAndYearUtil::ConvertJsTimeToCarbon($value);
        }
    }

    public function major(){
        return $this->belongsTo(Major::class);
    }

    public function school(){
        return $this->belongsTo(School::class);
    }

    /**
     * 招生工作负责人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager(){
        return $this->belongsTo(User::class,'manager_id');
    }

    /**
     * 录取工作负责人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enrolmentManager(){
        return $this->belongsTo(User::class,'enrol_manager');
    }

    public function courseMajor()
    {
        return $this->hasMany(CourseMajor::class,'major_id', 'major_id');
    }

    /**
     * 计划的已申请人数自减给定数目
     *
     * @param int $num
     * @return bool
     */
    public function appliedCountDecrease($num = 1){
        $count = $this->applied_count - $num;
        if($count > -1){
            $this->applied_count = $count;
            return $this->save();
        }
        return false;
    }
}
