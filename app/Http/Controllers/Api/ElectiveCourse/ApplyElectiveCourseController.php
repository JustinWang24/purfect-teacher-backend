<?php

namespace App\Http\Controllers\Api\ElectiveCourse;

use App\Dao\ElectiveCourses\TeacherApplyElectiveCourseDao;
use App\Http\Requests\School\TeacherApplyElectiveCourseRequest;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApplyElectiveCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 教师创建申请页面
     * @param TeacherApplyElectiveCourseRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(TeacherApplyElectiveCourseRequest $request)
    {

        $validated = $request->validated();
        //获取当前学校的教师数据，实际给定的是user对象
        $teacher = $request->user();
        $schoolId = $teacher->getSchoolId();
        $applyData = $validated;
        $dao = new TeacherApplyElectiveCourseDao();
        $applyData['course']['school_id'] = $schoolId;
        $applyData['course']['max_num'] = $applyData['course']['max_number'];
        $applyData['course']['start_year'] = $applyData['course']['start_year']??date("Y");

        if(empty($applyData['course']['id'])){
            // 创建新选修课程申请
            $user = $request->user();
            $applyData['course']['status'] = $applyData['course']['status']??$dao->getDefaultStatusByRole($user);
            $result = $dao->createTeacherApplyElectiveCourse($applyData);
        }
        else{
            // 更新操作
            $result = $dao->updateTeacherApplyElectiveCourse($applyData);
        }

        $apply = $result->getData();

        return $result->isSuccess() ?
            JsonBuilder::Success(['id'=>$apply->id ?? $applyData['id']])
            : JsonBuilder::Error($result->getMessage());
    }

    /**
     * 将通过申请的选修课发布到课程course中
     * http://teacher.backend.com/api/elective-course/publish/22
     * @param Request $request
     * @param $id
     * @return string
     */
    public function publish(Request $request, $id)
    {

        $applyDao = new TeacherApplyElectiveCourseDao();
        $result  = $applyDao->publishToCourse($id);
        return $result->isSuccess() ?
            JsonBuilder::Success(['id'=>$id])
            : JsonBuilder::Error($result->getMessage());


    }
}
