<?php
namespace App\Dao\Students;

use App\Utils\JsonBuilder;
use App\Models\Students\Application;
use App\Utils\ReturnData\MessageBag;

class ApplicationDao
{

    /**
     * 创建
     * @param $data
     * @return MessageBag
     */
    public function create($data) {
        $result = Application::create($data);
        if($result) {
            return new MessageBag(JsonBuilder::CODE_SUCCESS, '创建成功',$result);
        } else {
            return new MessageBag(JsonBuilder::CODE_ERROR, '创建失败');
        }
    }
}
