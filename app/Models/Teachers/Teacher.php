<?php

namespace App\Models\Teachers;
use App\Models\Teachers\Performance\TeacherPerformance;
use App\Models\Users\UserOrganization;
use App\User;
use App\Utils\Pipeline\IFlow;

class Teacher extends User
{
    protected $table = 'users';

    /**
     * 老师需要使用的流程的类型集合
     * @return array
     */
    public static function FlowTypes(){
        return [IFlow::TYPE_OFFICE,IFlow::TYPE_TEACHER_ONLY];
    }

    public function performances(){
        return $this->hasMany(TeacherPerformance::class, 'user_id')->orderBy('year','desc');
    }

    public function userOrganization(){
        return $this->hasOne(UserOrganization::class);
    }
}
