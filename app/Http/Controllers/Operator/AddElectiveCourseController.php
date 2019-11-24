<?php

namespace App\Http\Controllers\Operator;

use App\Dao\ElectiveCourses\TeacherApplyElectiveCourseDao;
use App\Http\Requests\School\TeacherApplyElectiveCourseRequest;
use App\Models\ElectiveCourses\TeacherApplyElectiveCourse;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddElectiveCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 教师创建申请页面
     * @param TeacherApplyElectiveCourseRequest $request
     * @return string
     */
    public function create(TeacherApplyElectiveCourseRequest $request)
    {

        $validated = $request->validated();
        //获取当前学校的教师数据，实际给定的是user对象
        $user = $request->user();
        $schoolId = $user->getSchoolId()??$request->session()->get('school.id');
        if (empty($schoolId))
        {
            return  JsonBuilder::Error('没有获取到学校数据，请重试');
        }

        $applyData = $validated;
        $dao = new TeacherApplyElectiveCourseDao();
        $applyData['course']['school_id'] = $schoolId;
        $applyData['course']['max_num'] = $applyData['course']['max_number'];
        $applyData['course']['start_year'] = $applyData['course']['start_year']??date("Y");
        // 创建新选修课程申请
        $applyData['course']['status'] = $applyData['course']['status'] ?? $dao->getDefaultStatusByRole($user);
        $result = $dao->createTeacherApplyElectiveCourse($applyData);
        $apply = $result->getData();
        //如果是学校管理员添加的申请那么直接发布
        if ($result->isSuccess() && $dao->getDefaultStatusByRole($user) == TeacherApplyElectiveCourse::STATUS_VERIFIED)
        {
            $result  = $dao->publishToCourse($apply->id);
        }
        return $result->isSuccess() ?
            JsonBuilder::Success(['id' => $apply->id])
            : JsonBuilder::Error($result->getMessage());
    }

    /**
     * 将通过申请的选修课发布到课程course中
     * http://teacher.backend.com/api/elective-course/publish/22
     * @param Request $request
     * @param $id
     * @return string
     */
    public function approve(Request $request, $id)
    {
        $validatedData = $request->validate([
            'content' => 'nullable|max:255',
        ]);
        $user = $request->user();
        $schoolId = $user->getSchoolId()??$request->session()->get('school.id');

        $applyDao = new TeacherApplyElectiveCourseDao();
        $apply = $applyDao->getApplyById($id);
        if ($apply->school_id !== $schoolId) {
            return JsonBuilder::Error('您没有权限修改这个申请');
        }
        $content = $validatedData->content;
        $applyDao->approvedApply($id, $content);
        $result  = $applyDao->publishToCourse($id);
        return $result->isSuccess() ?
            JsonBuilder::Success(['id'=>$id])
            : JsonBuilder::Error($result->getMessage());
    }
}
