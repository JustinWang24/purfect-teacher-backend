<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 29/11/19
 * Time: 9:50 PM
 */

namespace App\Dao\Users;


use App\Models\Users\UserOrganization;

class UserOrganizationDao
{
    public function create($data){
        return UserOrganization::create($data);
    }

    public function update($id, $data){
        return UserOrganization::where('id',$id)->update($data);
    }

    public function delete($id){
        return UserOrganization::where('id',$id)->delete();
    }
}