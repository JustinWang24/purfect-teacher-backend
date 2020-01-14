<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 25/11/19
 * Time: 11:39 PM
 */

namespace App\Dao\Schools;

use App\Models\Schools\Organization;
use App\Models\Users\UserOrganization;
use Illuminate\Support\Collection;

class OrganizationDao
{
    public function __construct()
    {
    }

    /**
     * @param $schoolId
     * @param $idNameValuePair
     * @return Collection
     */
    public function getBySchoolId($schoolId, $idNameValuePair=false){
        if($idNameValuePair){
            return Organization::select(['id','name'])->where('school_id',$schoolId)->where('level','>',1)->orderBy('level','asc')->get();
        }
        return Organization::where('school_id',$schoolId)->orderBy('level','asc')->get();
    }

    /**
     * 获取指定级别的所有机构
     * @param $level
     * @param $schoolId
     * @return Collection
     */
    public function loadByLevel($level, $schoolId){
        return Organization::where('school_id',$schoolId)
            ->where('level',$level)
            ->get();
    }

    /**
     * 创建一个组织
     * @param $data
     * @return Organization
     */
    public function create($data){
        if(empty($data['parent_id'])){
            $data['parent_id'] = $this->getRoot($data['school_id'])->id;
        }
        return Organization::create($data);
    }


    /**
     * 删除组织及人员构成
     * @param $id
     * @return bool
     */
    public function deleteOrganization($id){
        $this->deleteBranches($id);
        Organization::where('id',$id)->delete();
        UserOrganization::where('organization_id',$id)->delete();
        return true;
    }


    /**
     * @param $parentId
     */
    public function deleteBranches($parentId){
        $orgs = Organization::where('parent_id',$parentId)->get();
        foreach ($orgs as $org) {
            $this->deleteOrganization($org->id);
        }
    }

    /**
     * @param $data
     * @param $id
     * @return mixed
     */
    public function update($data, $id){
        return Organization::where('id',$id)->update($data);
    }

    /**
     * @param $schoolId
     * @return Organization
     */
    public function getRoot($schoolId){
         $root = Organization::where('school_id',$schoolId)
            ->where('level',Organization::ROOT)
            ->where('parent_id',0)
            ->first();
         if(!$root){
             $root = (new SchoolDao())->createRootOrganization($schoolId);
         }
         return $root;
    }

    /**
     * @param $id
     * @return Organization
     */
    public function getById($id){
        return Organization::find($id);
    }

    /**
     * 获取学校组织机构的最大级别
     * @param $schoolId
     * @return int
     */
    public function getTotalLevel($schoolId){
        $org = Organization::where('school_id',$schoolId)->orderBy('level','desc')->first();
        return $org->level??0;
    }

    public function output(Organization $org){
        foreach ($org->branch as $branch){
            $branch->output();
            $this->output($branch);
        }
    }

    public function getByName($schoolId, $name) {
        return Organization::where('school_id', $schoolId)
            ->where('name','like', '%'.$name.'%')
            ->first();
    }

    public function getMembers($schoolId, $id) {
        return UserOrganization::where('school_id', $schoolId)
            ->where('organization_id', $id)
            ->with('user')
            ->get();
    }


    /**
     * @param $schoolId
     * @param $parentId
     * @return mixed
     */
    public function getByParentId($schoolId, $parentId) {
        $map = ['school_id'=>$schoolId];
        if(is_null($parentId)) {
            $map['level'] = 1;
        } else {
            $map['parent_id'] = $parentId;
        }
        return Organization::where($map)->get();
    }
}
