<?php

namespace App\Models\RecruitStudent;

use Illuminate\Database\Eloquent\Model;
use App\Models\Students\StudentProfile;
use App\Models\School;
use App\Models\Schools\Major;
use App\User;

class RegistrationInformatics extends Model
{
    protected $fillable = ['user_id', 'school_id', 'major_id', 'name', 'status', 'recruitment_plan_id', 'relocation_allowed', 'note'];

    const WAITING           = 1;    // 等待审核
    const REFUSED           = 2;    // 报名审核被拒绝
    const PASSED            = 3;    // 报名审核已通过
    const WAIT_FOR_APPROVAL = 3;    // 录取时 待录取
    const REJECTED          = 4;    // 被拒绝录取
    const APPROVED          = 5;    // 被录取
    const ENROLLED          = 6;    // 已报到

    const WAITING_TEXT              = '等待审核';
    const REFUSED_TEXT              = '报名审核被拒绝';
    const PASSED_TEXT               = '报名审核已通过';
    const WAIT_FOR_APPROVAL_TEXT    = '审核通过待录取';
    const REJECTED_TEXT             = '被拒绝录取';
    const APPROVED_TEXT             = '已录取';
    const ENROLLED_TEXT             = '已报到';

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
}
