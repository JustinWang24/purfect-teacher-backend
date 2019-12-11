<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 16/10/19
 * Time: 9:58 PM
 */

namespace App\Dao\Users;
use App\Models\Acl\Role;

class RoleDao
{
    /**
     * @param $slug
     * @return Role|null
     */
    public function getBySlug($slug){
        return Role::where('slug',$slug)->first();
    }

    /**
     * @param $userType
     * @return Role|null
     */
    public function getByUserType($userType){
        return Role::find($userType);
    }

    public function syncRolePermissions(){

    }
}