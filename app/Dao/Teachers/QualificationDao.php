<?php

namespace App\Dao\Teachers;

use App\Models\Teachers\TeacherQualification;
use App\Utils\JsonBuilder;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\MessageBag;

class QualificationDao
{

    public function create($data)
    {
        $result = TeacherQualification::create($data);
        if ($result) {
            return new  MessageBag(JsonBuilder::CODE_SUCCESS, '添加成功');
        } else {
            return new  MessageBag(JsonBuilder::CODE_ERROR,'添加失败');
        }
    }

    /**
     * @param $teacherId
     * @return mixed
     */
    public function getTeacherQualificationByTeacherId($teacherId)
    {
        return TeacherQualification::where('user_id', $teacherId)->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    /**
     * 删除
     * @param $id
     * @return
     */
    public function del($id)
    {
        return TeacherQualification::where('id', $id)->delete();
    }

}
