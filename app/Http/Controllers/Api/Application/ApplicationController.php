<?php


namespace App\Http\Controllers\Api\Application;


use App\Dao\Students\ApplicationTypeDao;
use App\Utils\JsonBuilder;
use App\Dao\Students\ApplicationDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\ApplicationRequest;

class ApplicationController extends Controller
{

    /**
     * 创建申请
     * @param ApplicationRequest $request
     * @return string
     */
    public function create(ApplicationRequest $request) {
        $data = $request->getApplicationFormData();
        $dao = new ApplicationDao();
        $result = $dao->create($data);
        if($result->isSuccess()) {
            return JsonBuilder::Success($result->getData(),'申请成功');
        } else {
            return JsonBuilder::Error($result->getMessage());
        }
    }


    /**
     * 获取申请类型列表
     * @param ApplicationRequest $request
     * @return string
     */
    public function applicationTypeList(ApplicationRequest $request) {
        $dao = new ApplicationTypeDao();
        $schoolId = $request->user()->getSchoolId();
        $list = $dao->getOpenTypeListBySchoolId($schoolId);
        foreach ($list as $key => $val) {
            $val->media;
        }
        $result = ['type'=>$list];
        return JsonBuilder::Success($result);

    }



}
