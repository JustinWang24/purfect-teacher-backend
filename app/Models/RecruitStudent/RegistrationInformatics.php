<?php

namespace App\Models\RecruitStudent;

use App\Models\Schools\RecruitmentPlan;
use Illuminate\Database\Eloquent\Model;
use App\Models\Students\StudentProfile;
use App\Models\School;
use App\Models\Schools\Major;
use App\User;

class RegistrationInformatics extends Model
{
    protected $fillable = [
        'user_id', 'school_id', 'major_id', 'name', 'status',
        'recruitment_plan_id', 'relocation_allowed', 'note',
    ];

    const USELESS           = 0;    // 申请作废
    const WAITING           = 1;    // 等待审核
    const REFUSED           = 2;    // 报名审核被拒绝
    const PASSED            = 3;    // 报名审核已通过
    const WAIT_FOR_APPROVAL = 3;    // 录取时 待录取
    const REJECTED          = 4;    // 被拒绝录取
    const APPROVED          = 5;    // 被录取
    const ENROLLED          = 6;    // 已报到

    const USELESS_TEXT              = '申请作废';
    const WAITING_TEXT              = '等待审核';
    const REFUSED_TEXT              = '报名审核被拒绝';
    const PASSED_TEXT               = '报名审核已通过';
    const WAIT_FOR_APPROVAL_TEXT    = '审核通过待录取';
    const REJECTED_TEXT             = '被拒绝录取';
    const APPROVED_TEXT             = '已录取';
    const ENROLLED_TEXT             = '已报到';

    public static function AllStatusStudent(){
        return [
            self::USELESS => self::USELESS_TEXT,
            self::WAITING => self::WAITING_TEXT,
            self::REFUSED => self::REFUSED_TEXT,
            self::PASSED => self::PASSED_TEXT,
            self::REJECTED => self::REJECTED_TEXT,
            self::APPROVED => self::APPROVED_TEXT,
            self::ENROLLED => self::ENROLLED_TEXT,
        ];
    }

    /**
     * 学生详情表
     */
    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class, 'user_id', 'user_id');
    }

    /**
     * 学校表
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function school()
    {
        return $this->hasOne(School::class, 'id', 'school_id');
    }

    /**
     *专业表
     */
    public function  major()
    {
        return $this->hasOne(Major::class, 'id', 'major_id');
    }

    /**
     * 用户表
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 报名表关联的学生资料数据
     */
    public function profile()
    {
        return $this->belongsTo(StudentProfile::class,'user_id','user_id');
    }

    /**
     * 关联的报名计划
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan(){
        return $this->belongsTo(RecruitmentPlan::class,'recruitment_plan_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lastOperator(){
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    /**
     * 获取状态文字
     * @return string
     */
    public function getStatusText(){
        return self::AllStatusStudent()[$this->status];
    }

    /**
     * 学生是否服从分配
     * @return boolean
     */
    public function isRelocationAllowed(){
        return $this->relocation_allowed;
    }
}
