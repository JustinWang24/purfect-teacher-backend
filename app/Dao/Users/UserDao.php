<?php
/**
 * Created by Justin
 */

namespace App\Dao\Users;
use App\Models\Users\GradeUser;
use App\User;
use App\Models\Acl\Role;
use Illuminate\Support\Facades\DB;

class UserDao
{
    private $protectedRoles = [Role::SUPER_ADMIN, Role::OPERATOR];

    /**
     * 根据用户的电话号码获取用户
     * @param string $mobile
     * @return User
     */
    public function getUserByMobile($mobile){
        return User::where('mobile',$mobile)->first();
    }

    /**
     * 根据用户的 id 或者 uuid 获取用户对象
     * @param string $idOrUuid
     * @return User|null
     */
    public function getUserByIdOrUuid($idOrUuid){
        if(is_string($idOrUuid) && strlen($idOrUuid) > 10){
            return $this->getUserByUuid($idOrUuid);
        }
        elseif ($idOrUuid){
            return $this->getUserById($idOrUuid);
        }
        return null;
    }

    /**
     * @param $uuid
     * @return User|null
     */
    public function getUserByUuid($uuid){
        return User::where('uuid',$uuid)->first();
    }

    /**
     * @param $id
     * @return User|null
     */
    public function getUserById($id){
        return User::find($id);
    }

	/**
     * 获取用户所在班级
     * @param $uuid
     * @return
     */
    public function getUserGradeByUuid($uuid)
    {
        return User::where('uuid', $uuid)->with('gradeUser')->first();
    }

    /**
     * 获取用户的所有角色, 返回值为角色的 slug
     * @param User|int|string $user
     * @return string[]|null
     */
    public function getUserRoles($user){
        if(is_object($user)){
            return $user->getRoles();
        }else{
            $user = $this->getUserByIdOrUuid($user);
            return $user ? $user->getRoles() : null;
        }
    }

    /**
     * 给用户赋予一个角色
     * @param User $user
     * @param int|string $roleId
     * @param bool $ignoreProtectedRoles
     * @return bool
     */
    public function assignRoleToUser(User $user, $roleId, $ignoreProtectedRoles = false){
        if($ignoreProtectedRoles || !in_array($roleId, $this->protectedRoles)){
            // 不提供给用户赋予超级管理员和运营人员角色的功能
            $user->assignRole($roleId);
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * 撤销用户的某个角色
     * @param User $user
     * @param int|string $roleId
     * @param bool $ignoreProtectedRoles
     * @return bool
     */
    public function revokeRoleFromUser(User $user, $roleId, $ignoreProtectedRoles = false){
        if($ignoreProtectedRoles || !in_array($roleId, $this->protectedRoles)){
            // 不提供给用户撤销 超级管理员和运营人员角色 的功能
            $user->revokeRole($roleId);
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * 返回 APP 用户身份
     * @param $userType
     * @return string
     */
    public function  getUserRoleName($userType)
    {
        switch ($userType) {
            case Role::TEACHER :
                return trans('AppName.teacher');
                break;
            case Role::VERIFIED_USER_STUDENT :
                return trans('AppName.student');
                break;
            // todo :: 用到了再补充, 用来获取用户身份的名字
            default: return "" ;
                break;
        }
    }

    /**
     * 获取指定学校的所有的教师的列表
     * @param $schoolId
     * @param bool $simple: 简单的返回值 id=>name 的键值对组合
     * @return \Illuminate\Support\Collection
     */
    public function getTeachersBySchool($schoolId, $simple = false){
        if($simple){
            return GradeUser::select(DB::raw('user_id as id, name'))
                ->where('school_id',$schoolId)
                ->where('user_type',Role::TEACHER)
                ->get();
        }
        return GradeUser::where('school_id',$schoolId)
            ->where('user_type',Role::TEACHER)->get();
    }
}
