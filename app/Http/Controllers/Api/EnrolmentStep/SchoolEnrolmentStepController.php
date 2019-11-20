<?php

namespace App\Http\Controllers\Api\EnrolmentStep;

use App\Dao\EnrolmentStep\SchoolEnrolmentStepDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\EnrolmentStep\SchoolEnrolmentStepRequest;
use App\Utils\JsonBuilder;

class SchoolEnrolmentStepController extends Controller
{

    /**
     * 创建迎新
     * @param SchoolEnrolmentStepRequest $request
     * @return string
     */
    public function saveEnrolment(SchoolEnrolmentStepRequest $request) {
        $data = $request->getFormData();
        $dao = new SchoolEnrolmentStepDao();

        if(empty($data['id'])) {
            // 创建
            $result = $dao->createSchoolEnrolmentStep($data);
        } else {
            // 修改
            $id = $data['id'];
            unset($data['id']);
            $result = $dao->updateSchoolEnrolmentStep($id, $data);
        }
        if($result->isSuccess()) {
            $return = $result->getData();
            return JsonBuilder::Success($return,$result->getMessage());
        } else {
            return JsonBuilder::Error($result->getMessage());
        }
    }


    public function getEnrolmentInfo(SchoolEnrolmentStepRequest $request) {
        $id = $request->getEnrolmentId();

        $dao = new SchoolEnrolmentStepDao();
        $info = $dao->getEnrolmentStepInfoById($id);
        return JsonBuilder::Success($info);
    }




    /**
     * 删除迎新
     * @param SchoolEnrolmentStepRequest $request
     * @return string
     */
    public function deleteEnrolment(SchoolEnrolmentStepRequest $request) {
        $id = $request->getEnrolmentId();
        $dao = new SchoolEnrolmentStepDao();
        $result = $dao->deleteSchoolEnrolmentStep($id);
        if($result->isSuccess()) {
            $return = $result->getData();
            return JsonBuilder::Success($return,$result->getMessage());
        } else {
            return JsonBuilder::Error($result->getMessage());
        }
    }


}
