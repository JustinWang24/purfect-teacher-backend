<?php

namespace App\BusinessLogic\EnrolmentStepLogic;

use App\Models\EnrolmentStep\SchoolEnrolmentStep;
use App\BusinessLogic\EnrolmentStepLogic\Impl\EnrolmentStepInfo;
use App\BusinessLogic\EnrolmentStepLogic\Impl\EnrolmentStepAfter;
use App\BusinessLogic\EnrolmentStepLogic\Impl\EnrolmentStepBefore;

class Factory
{
    public static function GetStepLogic($stepType, $id, $schoolId, $campusId, $schoolEnrolmentStepDao) {

        $logic = null;
        // 获取详情
        if($stepType == SchoolEnrolmentStep::STEP_INFO) {
            $logic = new EnrolmentStepInfo($id, $schoolEnrolmentStepDao);
        }
        // 上一步
        elseif ($stepType == SchoolEnrolmentStep::STEP_BEFORE) {
            $logic = new EnrolmentStepBefore($id, $schoolId, $campusId, $schoolEnrolmentStepDao);
        }
        // 下一步
        elseif ($stepType == SchoolEnrolmentStep::STEP_AFTER) {
            $logic = new EnrolmentStepAfter($id, $schoolId, $campusId, $schoolEnrolmentStepDao);
        }

        return $logic;
    }
}
