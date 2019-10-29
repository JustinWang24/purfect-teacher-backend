<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 22/10/19
 * Time: 3:19 PM
 */

namespace App\Http\Controllers\Operator\TimeTables;
use App\Dao\Schools\GradeDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\TimeTable\TimetableRequest;
use App\Dao\Schools\SchoolDao;

class TimetablesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 课程表管理的默认首页
     * @param TimetableRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manager(TimetableRequest $request){
        $schoolDao = new SchoolDao($request->user());
        $school = $schoolDao->getSchoolByUuid($request->uuid());
        $this->dataForView['pageTitle'] = $school->name . ' 课程表管理';
        $this->dataForView['needManagerNav'] = true;
        $this->dataForView['school'] = $school;
        $this->dataForView['app_name'] = 'time_slots_app';
        return view('school_manager.timetable.manager', $this->dataForView);
    }

    /**
     * 添加课程表项的视图, 同时可以预览课程表
     * @param TimetableRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function preview(TimetableRequest $request){
        $schoolDao = new SchoolDao($request->user());
        $school = $schoolDao->getSchoolByUuid($request->uuid());
        $this->dataForView['pageTitle'] = $school->name . ' 课程表管理';
        $this->dataForView['needManagerNav'] = true;
        $this->dataForView['school'] = $school;
        $this->dataForView['app_name'] = 'timetable_preview_app';
        return view('school_manager.timetable.manager', $this->dataForView);
    }

    /**
     * 查看某个班的课程表
     * @param TimetableRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view_grade_timetable(TimetableRequest $request){
        // 这是一个单独的页面, 不是课程表管理页面
        $gradeDao = new GradeDao($request->user());
        $grade = $gradeDao->getGradeById($request->uuid());

        $this->dataForView['pageTitle'] = $grade->name . ' 课程表';
        $this->dataForView['needManagerNav'] = false;
        $this->dataForView['grade'] = $grade;
        $this->dataForView['school'] = $grade->school;
        $this->dataForView['term'] = $request->has('term') ? $request->get('term') : 1; // 默认加载第一学期
        $this->dataForView['app_name'] = 'timetable_grade_view_app';
        return view('school_manager.timetable.manager', $this->dataForView);
    }
}