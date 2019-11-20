<?php
namespace App\Dao\EnrolmentStep;

use App\Models\EnrolmentStep\EnrolmentStep;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;

class EnrolmentStepDao
{

    /**
     * 创建
     * @param $data
     * @return mixed
     */


    /**
     * 创建
     * @param $data
     * @return MessageBag
     */
    public function create($data) {
        $re = $this->getByName($data['name']);
        if(!is_null($re)) {
            return new MessageBag(JsonBuilder::CODE_ERROR,'请勿重复添加');
        }
        $result = EnrolmentStep::create($data);
        if($result) {
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'创建成功',$result);
        } else {
            return new MessageBag(JsonBuilder::CODE_ERROR,'创建失败');
        }
    }


    /**
     * @param $name
     * @return mixed
     */
    public function getByName($name) {
        return EnrolmentStep::where('name',$name)->first();
    }


    /**
     * 获取列表
     * @return EnrolmentStep
     */
    public function getEnrolmentStepAll() {
        return EnrolmentStep::get();
    }
}
