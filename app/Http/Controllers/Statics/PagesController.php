<?php

namespace App\Http\Controllers\Statics;

use App\Dao\RecruitmentPlan\RecruitmentPlanDao;
use App\Dao\Schools\MajorDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\Users\UserDao;
use App\Http\Requests\MyStandardRequest;
use App\Models\RecruitStudent\RecruitNote;
use App\Models\Schools\SchoolConfiguration;
use App\User;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    /**
     * 用户分享的用来展示某学校招生信息的页面
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function school_majors_list(Request $request){
        $dao = new SchoolDao();
        $school = $dao->getSchoolById($request->get('school_id'));
        $this->dataForView['pageTitle'] = ''; // 招生简章
        $this->dataForView['school'] = $school;

        $this->dataForView['appName'] = 'student_registration_app';
        $this->dataForView['api_token'] = Auth::user()->api_token ?? null;

        return view('h5_apps.student.registration_app', $this->dataForView);
    }

    /**
     * 报名须知页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function school_enrolment_notes(Request $request){
        $token = $request->get('api_token');
        $school_id = 0;
        if ($token) {
            $getUserByApiToken = (new UserDao())->getUserByApiToken($token);
            if (!empty($getUserByApiToken)) {
                $gradeUserOneInfo = $getUserByApiToken->gradeUserOneInfo;
                $school_id = isset($gradeUserOneInfo->school_id) ? $gradeUserOneInfo->school_id : $school_id;
            }
        }

        $this->dataForView['note'] = RecruitNote::where('school_id',$school_id)->first();

        return view('h5_apps.student.school_enrolment_notes', $this->dataForView);
    }

    /**
     * 招生简章h5页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function school_recruitment_intro(Request $request){
        $token = $request->get('api_token');
        $school_id = 0;
        if ($token) {
            $getUserByApiToken = (new UserDao())->getUserByApiToken($token);
            if (!empty($getUserByApiToken)) {
                $gradeUserOneInfo = $getUserByApiToken->gradeUserOneInfo;
                $school_id = isset($gradeUserOneInfo->school_id) ? $gradeUserOneInfo->school_id : $school_id;
            }
        }
        $this->dataForView['pageTitle'] = ''; // 招生简章
        $this->dataForView['api_token'] = Auth::user()->api_token ?? null;
        $this->dataForView['config'] = SchoolConfiguration::where('school_id',$school_id)->first();

        return view('h5_apps.student.school_recruitment_intro', $this->dataForView);
    }

    /**
     * 学生点击后直接显示专用报名用的界面
     * http://new-doc.pftytx.com/web/#/1?page_id=59 报名界面
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function school_enrol_plan(Request $request){
        // 会提供plan_id
        $plan = (new RecruitmentPlanDao())->getPlan($request->get('id'));
        $this->dataForView['plan'] = $plan;
        $this->dataForView['school'] = $plan->school;
        $this->dataForView['pageTitle'] = ''; // 报名
        $this->dataForView['appName'] = 'student_registration_app';
        $this->dataForView['api_token'] = $request->get('api_token',null);
        return view('h5_apps.student.registration_form_app', $this->dataForView);
    }

  /**
   * 教师端-我的-管理系统
   * @param Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
    public function system(Request $request)
    {
        $user = $request->user('api');
        if (is_null($user)) {
          return  JsonBuilder::Error('未找到用户');
        }

        Auth::login($user);
        return redirect()->route('home');
    }

}
