<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 6/11/19
 * Time: 5:59 PM
 */

namespace App\BusinessLogic\RecruitmentPlan;


use App\BusinessLogic\RecruitmentPlan\Contract\IPlansLoaderLogic;
use App\BusinessLogic\RecruitmentPlan\Impl\BackendLogic;
use App\BusinessLogic\RecruitmentPlan\Impl\FrontendLogic;
use App\Http\Requests\RecruitStudent\PlanRecruitRequest;

class PlansLoader
{
    /**
     * @param PlanRecruitRequest $request
     * @return IPlansLoaderLogic
     */
    public static function GetInstance(PlanRecruitRequest $request){
        $instance = null;
        if($request->getVersion()){
            // 从前端页面传来的
            $instance = new FrontendLogic($request);
        }
        else{
            // 从管理后台传来的
            $instance = new BackendLogic($request);
        }
        return $instance;
    }
}