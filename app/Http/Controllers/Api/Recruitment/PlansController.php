<?php

namespace App\Http\Controllers\Api\Recruitment;

use App\BusinessLogic\RecruitmentPlan\PlansLoader;
use App\Dao\RecruitmentPlan\RecruitmentPlanDao;
use App\Dao\RecruitStudent\ConsultDao;
use App\Dao\Schools\OrganizationDao;
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
        $logic = PlansLoader::GetInstance($request, $request->getYear());
        $plans = [];
        if($logic){
            $plans = $logic->getPlans();
        }

        $user = $request->user('api');

        return JsonBuilder::Success([
            'plans'=>$plans,
            'banner'=>['image'=>''],
            'school_id'=>$user ? $user->getSchoolId() : $request->getSchoolId()
        ]);
    }

    /**
     * @param PlanRecruitRequest $request
     * @return string
     */
    public function save_plan(PlanRecruitRequest $request){
        $formData = $request->getNewOrUpdatedPlanForm();
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
     * 根据 id 获取招生计划详情: 后端用
     * @param PlanRecruitRequest $request
     * @return string
     */
    public function get_plan(PlanRecruitRequest $request){
        $logic = PlansLoader::GetInstance($request, true);
        return JsonBuilder::Success(['plan'=>$logic->getPlanDetail()]);
    }


    /**
     * 根据 id 获取招生计划: 前端页面用
     * @param PlanRecruitRequest $request
     * @return string
     */
    public function get_plan_front(PlanRecruitRequest $request){
        $logic = PlansLoader::GetInstance($request, false);
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

    /**
     * 招生咨询接口
     * @param Request $request
     * @return string
     */
    public function qa(Request $request){
        $dao = new ConsultDao();
        $schoolId = 1;
        try{
            $schoolId = $request->user('api')->getSchoolId();
        }catch (\Exception $e){

        }
        $qa = $dao->getConsultById($schoolId, true);

        // 招生就业办的联系方式
        $orgDao = new OrganizationDao();
        $org = $orgDao->getByName($schoolId, '招生');
        $contact = [
            'name'=>'',
            'phone'=>'',
        ];

        if($org){
            $contact['name'] = $org->name;
            $contact['phone'] = $org->phone;
        }

        return JsonBuilder::Success(['qa'=>$qa, 'contact'=>$contact]);
    }
}
