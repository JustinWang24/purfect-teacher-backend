<?php

namespace App\Models\RecruitStudent;

use Illuminate\Database\Eloquent\Model;
use App\Models\Students\StudentProfile;
use App\Models\School;
use App\Models\Schools\Major;
use App\User;

class RegistrationInformatics extends Model
{
    protected $fillable = ['user_id', 'school_id', 'major_id', 'name', 'whether_adjust', 'status'];

     const PAGE_NUMBER = 10;

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
