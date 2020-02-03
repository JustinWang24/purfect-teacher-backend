<?php

namespace App\Http\Controllers\Statics;

use App\Dao\Schools\SchoolDao;
use App\Models\RecruitStudent\RecruitNote;
use App\Models\Schools\SchoolConfiguration;
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
        $this->dataForView['pageTitle'] = ''; // 报名须知
        $this->dataForView['api_token'] = Auth::user()->api_token ?? null;

        $this->dataForView['note'] = RecruitNote::where('school_id',1)->first();

        return view('h5_apps.student.school_enrolment_notes', $this->dataForView);
    }

    /**
     * 招生简章h5页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function school_recruitment_intro(Request $request){
        $this->dataForView['pageTitle'] = ''; // 招生简章
        $this->dataForView['api_token'] = Auth::user()->api_token ?? null;

        $this->dataForView['config'] = SchoolConfiguration::where('school_id',1)->first();

        return view('h5_apps.student.school_recruitment_intro', $this->dataForView);
    }

    public function school_enrol_plan(Request $request){
        dd(1111);
    }
}
