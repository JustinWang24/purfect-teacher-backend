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

    const WAIT_EXAMINE = 1;
    const ADOPT = 2;          // 报名时 已通过
    const WAIT_ADMISSION = 2; // 录取时 待录取
    const NOT_PASS = 3;
    const ADMISSION = 4;
    const WAIT_EXAMINE_TEXT = '等待审核';
    const ADOPT_TEXT = '已通过';
    const NOT_PASS_TEXT = '未通过';
    const WAIT_ADMISSION_TEXT = '待录取';
    const ADMISSION_TEXT = '已录取';

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
