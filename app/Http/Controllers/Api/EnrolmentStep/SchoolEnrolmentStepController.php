<?php

namespace App\Http\Controllers\Api\EnrolmentStep;

use App\BusinessLogic\EnrolmentStepLogic\Factory;
use App\Dao\EnrolmentStep\SchoolEnrolmentStepDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\EnrolmentStep\SchoolEnrolmentStepRequest;
use App\Models\EnrolmentStep\SchoolEnrolmentStep;
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


    /**
     * 获取步骤详情/上一步、下一步
     * @param SchoolEnrolmentStepRequest $request
     * @return string
     */
    public function getEnrolmentInfo(SchoolEnrolmentStepRequest $request) {
        $id = $request->getEnrolmentId();
        $step = $request->get('step_type',0);
        $schoolId = $request->get('school_id');
        $campusId = $request->get('campus_id');
        $dao = new SchoolEnrolmentStepDao();
        $logic = Factory::GetStepLogic($step, $id, $schoolId, $campusId, $dao);
        $result = $logic->getData();
        if($result->isSuccess()) {
            $data = $result->getData();
            $msg = $result->getMessage();
            return JsonBuilder::Success($data,$msg);
        } else {
            $msg = $result->getMessage();
            return JsonBuilder::Error($msg);
        }
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


    /**
     * 更新迎新步骤排序
     * @param SchoolEnrolmentStepRequest $request
     * @return string
     */
    public function updateSort(SchoolEnrolmentStepRequest $request) {
        $sort = $request->getFormData();
        $dao = new SchoolEnrolmentStepDao();
        $result = $dao->updateStepSort($sort);
        if($result->isSuccess()) {
            return JsonBuilder::Success($result->getMessage());
        } else {
            return JsonBuilder::Error($result->getMessage());
        }
    }


}
