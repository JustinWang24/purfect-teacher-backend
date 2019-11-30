<?php

namespace App\Http\Controllers\Api\EnrolmentStep;

use App\Dao\EnrolmentStep\EnrolmentStepDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Utils\JsonBuilder;

class EnrolmentStepController extends Controller
{

    /**
     * 获取系统迎新列表
     * @param MyStandardRequest $request
     * @return string
     */
    public function getEnrolmentStepList(MyStandardRequest $request) {
        $user = $request->user();
        // todo 判断是否为老师或更高权限
        $dao = new EnrolmentStepDao();
        $result = $dao->getEnrolmentStepAll();
        return JsonBuilder::Success($result,'请求成功');
    }
}
