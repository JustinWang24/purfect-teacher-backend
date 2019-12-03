<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 29/11/19
 * Time: 9:50 PM
 */

namespace App\Dao\Users;


use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use App\Models\Users\UserOrganization;

class UserOrganizationDao
{

    /**
     * @param $data
     * @return MessageBag
     */
    public function create($data){
        $info = $this->getUserOrganization($data['user_id'], $data['school_id']);
        if(!is_null($info)) {
            return new MessageBag(JsonBuilder::CODE_ERROR, '请勿重复添加',$info);
        }
        $re = UserOrganization::create($data);
        if($re) {
            return new MessageBag(JsonBuilder::CODE_SUCCESS, '创建成功',$re);
        } else {
            return new MessageBag(JsonBuilder::CODE_SUCCESS, '创建失败');
        }
    }

    public function update($id, $data){
        return UserOrganization::where('id',$id)->update($data);
    }

    public function delete($id){
        return UserOrganization::where('id',$id)->delete();
    }

    public function getUserOrganization($userId, $schoolId) {
        $map = ['user_id'=>$userId, 'school_id'=>$schoolId];
        return UserOrganization::where($map)->first();
    }
}
