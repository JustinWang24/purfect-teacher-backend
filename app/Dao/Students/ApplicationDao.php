<?php
namespace App\Dao\Students;

use App\Utils\JsonBuilder;
use App\Models\Students\Application;
use App\Utils\Misc\ConfigurationTool;
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


    /**
     * 根据学校获取申请
     * @param $schoolId
     * @return mixed
     */
    public function getApplicationBySchoolId($schoolId) {
        return Application::where('school_id', $schoolId)->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }


    /**
     * 获取详情
     * @param $id
     * @return mixed
     */
    public function getApplicationById($id) {
        return Application::where('id',$id)->first();
    }

    /**
     * 修改状态
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateStatusById($id, $data) {
        return Application::where('id', $id)->update($data);
    }





}
