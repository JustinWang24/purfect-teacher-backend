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
        $re = null;
        $info = $this->getUserOrganization($data['user_id'], $data['school_id'], $data['organization_id']);
        if(!is_null($info)) {
            return new MessageBag(JsonBuilder::CODE_ERROR, $data['name'].'已经属于当前的机构/部门, 请勿重复添加',$info);
        }else{
            $re = UserOrganization::create($data);
        }

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

    public function getUserOrganization($userId, $schoolId, $organizationId = null) {
        $map = ['user_id'=>$userId, 'school_id'=>$schoolId];
        if ($organizationId) {
            $map['organization_id'] = $organizationId;
        }
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


    /**
     * 获取部门下的人员
     * @param $schoolId
     * @param $organId
     * @return mixed
     */
    public function getOrganUserByOrganId($schoolId, $organId) {
        $field = ['user_id as id', 'name'];
        $map = ['school_id'=>$schoolId, 'organization_id'=>$organId];
        return UserOrganization::where($map)
            ->select($field)->get();
    }
}
