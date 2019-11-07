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
     * @param $back: 前端逻辑还是后端逻辑
     * @return IPlansLoaderLogic
     */
    public static function GetInstance(PlanRecruitRequest $request, $back = false){
        $instance = null;
        if($back){
            // 从管理后台传来的, 会提交 year 作为查询参数, 而前端不会
            $instance = new BackendLogic($request);
        }
        else{
            // 从前端页面传来的
            $instance = new FrontendLogic($request);
        }
        return $instance;
    }
}