<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 29/11/19
 * Time: 9:50 PM
 */

namespace App\Dao\Users;


use App\User;
use App\Utils\JsonBuilder;
use App\Utils\Misc\Contracts\Title;
use App\Utils\ReturnData\MessageBag;
use App\Models\Users\UserOrganization;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * 获取校长
     * @param $schoolId
     * @return User|null
     */
    public function getPrinciple($schoolId){
        $userOrg = UserOrganization::where('school_id',$schoolId)
            ->where('title_id',Title::SCHOOL_PRINCIPAL_ID)
            ->first();

        return $userOrg ? $userOrg->user : null;
    }

    /**
     * 获取学校的书记
     * @param $schoolId
     * @return null
     */
    public function getCoordinator($schoolId){
        $userOrg = UserOrganization::where('school_id',$schoolId)
            ->where('title_id',Title::SCHOOL_COORDINATOR_ID)
            ->first();

        return $userOrg ? $userOrg->user : null;
    }

    /**
     * 获取副校长
     * @param $schoolId
     * @return Collection
     */
    public function getDeputyPrinciples($schoolId){
        $usersOrg = UserOrganization::where('school_id',$schoolId)
            ->where('title_id',Title::SCHOOL_DEPUTY_ID)
            ->with('user')
            ->get();
        return $usersOrg;
    }

    /**
     * 获取部门经理
     * @param $orgId
     * @return UserOrganization
     */
    public function getOrganizationManager($orgId){
        return UserOrganization::where('organization_id',$orgId)
            ->where('title_id',Title::ORGANIZATION_LEADER_ID)
            ->with('user')
            ->first();
    }

    /**
     * 获取部门付经理
     * @param $orgId
     * @return Collection
     */
    public function getOrganizationDeputies($orgId){
        return UserOrganization::where('organization_id',$orgId)
            ->where('title_id',Title::ORGANIZATION_DEPUTY_ID)
            ->with('user')
            ->get();
    }
}
