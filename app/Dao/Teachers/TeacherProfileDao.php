<?php

namespace App\Dao\Teachers;

use App\User;
use App\Models\Teachers\TeacherProfile;

/**
 * Class ShoolDao
 * @package App\Dao\Users
 */
class TeacherProfileDao
{

    /**
     * 根据 uuid 或者 id 获取教师详情
     * @param $idOrUuid
     * @return School|null
     */
    public  function getTeacherProfileByTeacherIdOrUuid($teacherIdOrUuid)
    {
        if(is_string($teacherIdOrUuid) && strlen($teacherIdOrUuid) > 10){
            return TeacherProfile::where('uuid', $teacherIdOrUuid)->first();
        }
        elseif (is_int($teacherIdOrUuid)){
            return TeacherProfile::where('teacher_id', $teacherIdOrUuid)->first();
        }
        return null;
    }




}
