<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 9/10/19
 * Time: 10:13 PM
 */

namespace App\Dao\Users;
use App\User;
use App\Models\Acl\Role;

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
            return User::where('uuid',$idOrUuid)->first();
        }
        elseif (is_int($idOrUuid)){
            return User::find($idOrUuid);
        }
        return null;
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
}