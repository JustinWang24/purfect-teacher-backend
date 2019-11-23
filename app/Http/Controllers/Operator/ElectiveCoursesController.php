<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 23/11/19
 * Time: 8:09 PM
 */

namespace App\Http\Controllers\Operator;

use App\Dao\ElectiveCourses\TeacherApplyElectiveCourseDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Models\ElectiveCourses\ApplyCourseArrangement;
use App\Utils\JsonBuilder;

class ElectiveCoursesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 管理报名表的 action
     * @param MyStandardRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function management(MyStandardRequest $request){
        // 获取学校
        $applications = (new TeacherApplyElectiveCourseDao())->getPaginatedApplications($request->getSchoolId());
        $this->dataForView['applications'] = $applications;
        $this->dataForView['pageTitle'] = '选修课申请表';
        return view('school_manager.elective_course_applications.list', $this->dataForView);
    }

    public function delete_arrangement(MyStandardRequest $request){
        $dao = new TeacherApplyElectiveCourseDao();
        return $dao->deleteArrangementItem($request->get('item_id')) ? JsonBuilder::Success()
            : JsonBuilder::Error();
    }
}