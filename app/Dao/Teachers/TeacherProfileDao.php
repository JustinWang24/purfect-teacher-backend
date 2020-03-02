<?php

namespace App\Dao\Teachers;

use App\Models\Acl\Role;
use App\Models\Users\GradeUser;
use App\Models\Teachers\TeacherProfile;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class ShoolDao
 * @package App\Dao\Users
 */
class TeacherProfileDao
{
    /**
     * 根据 uuid 或者 id 获取教师详情
     * @param $teacherIdOrUuid
     * @return TeacherProfile|null
     */
    public  function getTeacherProfileByTeacherIdOrUuid($teacherIdOrUuid)
    {
        if(is_string($teacherIdOrUuid) && strlen($teacherIdOrUuid) > 10){
            return TeacherProfile::where('uuid', $teacherIdOrUuid)->first();
        }
        elseif (is_int($teacherIdOrUuid)){
            return TeacherProfile::where('user_id', $teacherIdOrUuid)->first();
        }
        return null;
    }

    /**
     * 搜索老师
     * @param $name
     * @param $schoolId
     * @return \Illuminate\Support\Collection
     */
    public function searchTeacherByNameSimple($name, $schoolId){

        return User::where('name','like', $name.'%')
            ->where('type', Role::TEACHER)
            ->leftJoin('teacher_profiles as teacher', 'teacher.user_id', '=', 'users.id')
            ->where('teacher.school_id', $schoolId)
            ->select(['users.id', 'users.name'])
            ->get();
    }

    /**
     * 创建老师的 Profile 模型
     * @param $data
     * @return TeacherProfile
     */
    public function createProfile($data){
        return TeacherProfile::create($data);
    }

    /**
     * 修改老师信息
     * @param $userId
     * @param $profile
     * @return mixed
     */
    public function updateTeacherProfile($userId, $profile)
    {
        return TeacherProfile::where('user_id',$userId)->update($profile);
    }

    /**
     * @param $idNumber
     * @return mixed
     */
    public function getTeacherProfileByIdNumber($idNumber)
    {
        return TeacherProfile::where('id_number', $idNumber)->first();
    }

    /**
     * @param $schoolId
     * @return mixed
     */
    public function getTeacherProfileBySchoolId($schoolId)
    {
        return TeacherProfile::where('school_id', $schoolId)->with(['user' => function ($query) {
                $query->where('type', Role::TEACHER);
        }])->get();
    }


    /**
     * @param $userIds
     * @return mixed
     */
    public function getTeacherProfileByUserIds($userIds) {
        return TeacherProfile::whereIn('user_id', $userIds)->get();
    }
}
