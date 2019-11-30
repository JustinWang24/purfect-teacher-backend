<?php

namespace App\Http\Controllers\Api\ElectiveCourse;

use App\Dao\ElectiveCourses\TeacherApplyElectiveCourseDao;
use App\Dao\Users\UserDao;
use App\Http\Requests\School\TeacherApplyElectiveCourseRequest;
use App\User;
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
     * 教师/管理员 创建申请页面
     * @param TeacherApplyElectiveCourseRequest $request
     * @return string
     */
    public function create(TeacherApplyElectiveCourseRequest $request)
    {
        $applyData = $request->validated();
        //获取当前学校的教师数据，实际给定的是user对象
        /**
         * @var User $user
         */
        $user = $request->user();
        $isAdminAction = $user->isSchoolAdminOrAbove();
        if($isAdminAction){
            // 如果是管理员, 则表示不是老师主动申请, 因此要查询一下, 得到老师
            $userDao = new UserDao();
            $user = $userDao->getUserById($applyData['course']['teacher_id']);
        }
        $dao = new TeacherApplyElectiveCourseDao();

        $applyData['course']['teacher_name'] = $user->name ?? 'n.a';
        $applyData['course']['start_year'] = $applyData['course']['start_year']??date("Y");

        if(empty($applyData['course']['id'])){
            // 创建新选修课程申请
            $applyData['course']['status'] = $applyData['course']['status']??$dao->getDefaultStatusByRole($request->user());
            $result = $dao->createTeacherApplyElectiveCourse($applyData);
            // 如果是管理员, 那么应该直接添加到课程数据库, 发布此课程
            if($isAdminAction){
                $result = $dao->publishToCourse($result->getData()->id);
            }
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

    public function load(Request $request){
        $applyDao = new TeacherApplyElectiveCourseDao();
        $app  = $applyDao->getApplyById($request->get('application_id'));
        return $app ?
            JsonBuilder::Success(['application'=>$app])
            : JsonBuilder::Error();
    }
}
