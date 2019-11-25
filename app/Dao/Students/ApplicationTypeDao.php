<?php

namespace App\Dao\Students;

use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use App\Models\Students\ApplicationType;

class ApplicationTypeDao
{

    /**
     * @param $data
     * @return MessageBag
     */
    public function create($data) {
        $exist = $this->getApplicationBySchoolIdAndName($data['school_id'], $data['name']);
        if(!is_null($exist)) {
            return new MessageBag(JsonBuilder::CODE_ERROR,'该类型已添加，请勿重复添加');
        }
        $re = ApplicationType::create($data);
        if($re) {
            return new MessageBag(JsonBuilder::CODE_SUCCESS, '创建成功');
        } else {
            return new MessageBag(JsonBuilder::CODE_ERROR, '创建失败');
        }
    }

    /**
     * 根据学校ID获取类型
     * @param $schoolId
     * @return mixed
     */
    public function getTypeBySchoolId($schoolId) {
        return ApplicationType::where('school_id',$schoolId)->get();
    }


    /**
     * 根据id获取详情
     * @param $id
     * @return mixed
     */
    public function getApplicationById($id) {
        return ApplicationType::where('id',$id)->first();
    }


    /**
     * 根据学校ID和name获取
     * @param $schoolId
     * @param $name
     * @return mixed
     */
    public function getApplicationBySchoolIdAndName($schoolId, $name) {
        $map = ['school_id'=>$schoolId, 'name'=>$name];
        return ApplicationType::where($map)->first();
    }


    /**
     * 编辑
     * @param $id
     * @param $data
     * @return MessageBag
     */
    public function updateById($id, $data) {
        $result = ApplicationType::where('id',$id)->update($data);
        if($result !== false) {
            return new MessageBag(JsonBuilder::CODE_SUCCESS, '编辑成功');
        } else {
            return new MessageBag(JsonBuilder::CODE_ERROR, '编辑失败');
        }
    }

}
