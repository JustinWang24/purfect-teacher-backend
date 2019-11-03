<?php

namespace App\Http\Controllers\Api\Recruitment;

use App\Dao\RecruitmentPlan\RecruitmentPlanDao;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlansController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function load_plans(Request $request){
        $schoolId = $request->get('school');
        $year = $request->has('year') ? $request->get('year') : date('Y');

        $dao = new RecruitmentPlanDao($schoolId);
        $plans = $dao->getPlansBySchool(
            $schoolId,
            $year,
            $request->get('pageNumber'),
            $request->get('pageSize')
        );

        return JsonBuilder::Success(['plans'=>$plans??[]]);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function save_plan(Request $request){
        $formData = $request->get('form');
        if(isset($formData['school_id']) && !empty($formData['school_id'])){
            $dao = new RecruitmentPlanDao($formData['school_id']);

            if(!empty($formData['id'])){
                $plan = $dao->updatePlan($formData);
                if($plan){
                    return JsonBuilder::Success(['id'=>$formData['id']]);
                }
            }
            else{
                unset($formData['id']);
                $plan = $dao->createPlan($formData);
                if($plan){
                    return JsonBuilder::Success(['id'=>$plan->id]);
                }
            }
            return JsonBuilder::Error('数据库操作失败, 请稍候再试');
        }
        return JsonBuilder::Error('没有指明具体学校, 无法操作!');
    }
}
