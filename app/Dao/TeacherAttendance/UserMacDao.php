<?php

namespace App\Dao\TeacherAttendance;


use App\Models\TeacherAttendance\UserMac;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;

class UserMacDao
{
    public function create($data)
    {
        try{
            $result = UserMac::create($data);
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'创建成功', $result);
        }catch (\Exception $e) {
            return new MessageBag(JsonBuilder::CODE_ERROR, $e->getMessage());
        }
    }
}
