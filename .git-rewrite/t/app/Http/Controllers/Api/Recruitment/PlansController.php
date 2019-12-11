<?php

namespace App\Http\Controllers\Api\Recruitment;

use App\BusinessLogic\RecruitmentPlan\PlansLoader;
use App\Dao\RecruitmentPlan\RecruitmentPlanDao;
use App\Http\Requests\RecruitStudent\PlanRecruitRequest;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlansController extends Controller
{
    /**
     * @param PlanRecruitRequest $request
     * @return string
     */
    public function load_plans(PlanRecruitRequest $request){
        $logic = PlansLoader::GetInstance($request);
        $plans = [];
        if($logic){
            $plans = $logic->getPlans();
        }
        return JsonBuilder::Success(['plans'=>$plans]);
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

    /**
     * 根据 id 获取招生计划
     * @param PlanRecruitRequest $request
     * @return string
     */
    public function get_plan(PlanRecruitRequest $request){
        $logic = PlansLoader::GetInstance($request);
        return JsonBuilder::Success(['plan'=>$logic->getPlanDetail()]);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function delete_plan(Request $request){
        $dao = new RecruitmentPlanDao(0);
        $done = $dao->deletePlan($request->get('plan'));
        return $done ? JsonBuilder::Success() : JsonBuilder::Error();
    }
}
