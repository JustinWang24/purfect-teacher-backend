<?php

namespace App\Http\Requests\RecruitStudent;

use App\Dao\RecruitmentPlan\RecruitmentPlanDao;
use App\Http\Requests\MyStandardRequest;
use App\Utils\Time\GradeAndYearUtil;

class PlanRecruitRequest extends MyStandardRequest
{
    private $plan = null;

    public function authorize()
    {
        return true;
    }

    /**
     * 从可能的地方获取 school 的 ID
     * @return mixed
     */
    public function getSchoolId()
    {
        return $this->has('school') ? $this->get('school') : null;
    }

    /**
     * 获取所要查询的是哪一年的招生简章
     * @return int|mixed
     */
    public function getYear(){
        return $this->has('year') ? $this->get('year') : null;
    }

    public function getMobile(){
        return $this->has('mobile') ? $this->get('mobile') : null;
    }

    public function getStudentIdNumber(){
        return  $this->has('id_number') ? $this->get('id_number') : null;
    }

    /**
     * 获取提交的表单数据
     * @return array
     */
    public function getSignUpFormData(){
        $form = $this->get('form');
        $form['recruitment_plan_id'] = $this->getPlanId();
        $plan = $this->getPlan();
        $form['school_id'] = $plan->school_id;
        $form['major_id'] = $plan->major_id;

        // 解析出生日的信息
        $bag = GradeAndYearUtil::IdNumberToBirthday($form['id_number']);
        if($bag->isSuccess()){
            $form['birthday'] = $bag->getData()->format('Y-m-d');
        }else{
            $form['birthday'] = null;
        }

        return $form;
    }

    /**
     * @return mixed
     */
    public function getPlan(){
        if(is_null($this->plan)){
            $planDao = new RecruitmentPlanDao(0);
            $this->plan = $planDao->getPlan($this->getPlanId());
        }
        return $this->plan;
    }

    /**
     * @return string|int
     */
    public function getPlanId(){
        return $this->get('recruitment_plan_id');
    }

    /**
     * 获取提交的用户 ID
     * @return string|int
     */
    public function getUserId(){
        return $this->has('user_id') ? $this->get('user_id') : null;
    }
}
