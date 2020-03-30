<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 6/11/19
 * Time: 6:10 PM
 */

namespace App\BusinessLogic\RecruitmentPlan\Impl;
use App\BusinessLogic\RecruitmentPlan\Contract\IPlansLoaderLogic;
use App\Http\Requests\RecruitStudent\PlanRecruitRequest;
use App\Dao\RecruitmentPlan\RecruitmentPlanDao;

class BackendLogic implements IPlansLoaderLogic
{
    private $schoolId;
    private $year;
    private $pageNumber;
    private $pageSize;
    private $userUuid;

    private $request;

    public function __construct(PlanRecruitRequest $request)
    {
        $this->schoolId = $request->getSchoolId();
        $this->year = $request->getYear();
        $this->pageNumber = $request->getPageNumber();
        $this->pageSize = $request->getPageSize();
        $this->userUuid = $request->uuid();
        $this->request = $request;
    }

    /**
     * 获取招生计划
     * @return array
     */
    public function getPlans()
    {
        $dao = new RecruitmentPlanDao($this->schoolId);

        $plans = $dao->getPlansBySchool(
            $this->schoolId,
            $this->userUuid,
            $this->year,
            $this->pageNumber,
            $this->pageSize
        );
        return $plans;
    }

    public function getPlanDetail()
    {
        $planId = $this->request->get('plan');
        $dao = new RecruitmentPlanDao(0);
        return $dao->getPlan($planId);
    }
}
