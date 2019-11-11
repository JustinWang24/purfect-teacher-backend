<?php
/**
 * 针对报名表的搜索类
 */

namespace App\BusinessLogic\QuickSearch\Impl;

use Illuminate\Http\Request;
use App\Dao\RecruitStudent\RegistrationInformaticsDao;

class RegistrationFormsQuickSearchLogic extends AbstractQuickSearchLogic
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function getFacilities()
    {
        return [];
    }

    public function getUsers()
    {
        $dao = new RegistrationInformaticsDao();
        return $dao->searchRegistrationFormsByStudentName($this->queryString, $this->schoolId);
    }

    public function getNextAction($registrationForm)
    {
        return route('teacher.registration.view',['uuid'=>$registrationForm->profile->uuid,'plan'=>$registrationForm->recruitment_plan_id]);
    }
}