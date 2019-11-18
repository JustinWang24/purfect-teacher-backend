<?php
namespace App\Http\Controllers\Operator;


use App\Dao\ElectiveCourses\TeacherApplyElectiveCourseDao;
use App\Dao\Schools\SchoolDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\TeacherApplyElectiveCourseRequest;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Integer;

/**
 * 获取学校的老师使用路由Route::any('/search-teachers','Api\School\UsersController@search_by_name')->name('api.school.search.teachers');
 * 获取学校的专业使用路由Route::any('/load-majors','Api\School\MajorsController@load_by_school')->name('api.school.load.majors');
 *
 * Class TeacherApplyElectiveCoursesController
 * @package App\Http\Controllers\Operator
 */
class TeacherApplyElectiveCoursesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 管理员创建申请页面
     * @param TeacherApplyElectiveCourseRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manager_create(TeacherApplyElectiveCourseRequest $request)
    {
        //获取当前学校的教师数据，实际给定的是user对象
        $teacher = $request->user();
        $schoolDao = new SchoolDao($request->user());
        $school = $schoolDao->getSchoolById($request->getSchoolId());
        //获取当前学校所有专业数据
        $this->dataForView['pageTitle'] = $school->name . '创建一个选修课申请';
        $this->dataForView['needManagerNav'] = true;
        $this->dataForView['school'] = $school;
        $this->dataForView['teacher'] = $teacher;
        return view('school_manager.apply.add', $this->dataForView);
    }
    //管理员编辑申请页面
    public function manager_edit(TeacherApplyElectiveCourseRequest $request,Integer $id)
    {
        $dao = new TeacherApplyElectiveCourseDao();
        $applyObj = $dao->getApplyById($id);
        //获取当前学校的教师数据，实际给定的是user对象
        $teacher = $request->user();
        $schoolDao = new SchoolDao($request->user());
        $school = $schoolDao->getSchoolById($request->$request->getSchoolId());

        $this->dataForView['pageTitle'] = $school->name . '创建一个选修课申请';
        $this->dataForView['needManagerNav'] = true;
        $this->dataForView['school'] = $school;
        $this->dataForView['teacher'] = $teacher;
        $this->dataForView['apply'] = $applyObj;
        return view('school_manager.apply.add', $this->dataForView);


    }

    /**
     * 申请列表页面
     * @param TeacherApplyElectiveCourseRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function apply_list(TeacherApplyElectiveCourseRequest $request)
    {
        $user = $request->user()->profile();
        $schoolDao = new SchoolDao($request->user());
        $school = $schoolDao->getSchoolByUuid($request->uuid());
        $this->dataForView['pageTitle'] = $school->name . ' 选修课申请管理';
        $this->dataForView['needManagerNav'] = true;
        $this->dataForView['school'] = $school;
        $this->dataForView['app_name'] = 'time_slots_app';
        return view('school_manager.apply.manager', $this->dataForView);
    }

    /**
     * 保存申请
     * @param TeacherApplyElectiveCourseRequest $request
     * @return string
     */
    public function apply_save(TeacherApplyElectiveCourseRequest $request)
    {
/*        $data = [];
        $data['teacher_id'] = 5;
        $data['major_id'] = 1;
        $data['code'] = '第一册';
        $data['name'] = '基础物理';
        $data['scores'] = 5;
        $data['year'] = 1;
        $data['term'] = 1;
        $data['desc'] = '基础物理是一门基础课';
        $data['open_num'] = 50;
        $data['groups'] = [
            [
                1=>[1=>[7,8],3=>[7]],
                2=>[1=>[7,8],3=>[7]],
                3=>[1=>[7,8],3=>[7]],
            ],
            [
                6=>[4=>[8],5=>[7,8],],
                8=>[4=>[8],5=>[7,8],],
                9=>[4=>[8],5=>[7,8],],
            ]
        ];*/


        $applyData = $request->get('apply');
//        $applyData = $data;
        $applyData['school_id'] = $request->getSchoolId();
        $dao = new TeacherApplyElectiveCourseDao();

        if(empty($applyData['id'])){
            // 创建新选修课程申请
            $user = $request->user();
            $applyData['status'] = $applyData['status']??$dao->getDefaultStatusByRole($user);
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
     * @param Request $request
     * @param $id
     * @return string
     */
    public function publish_apply(Request $request, $id)
    {
        $applyDao = new TeacherApplyElectiveCourseDao();
        $result  = $applyDao->publishToCourse($id);
        return $result->isSuccess() ?
            JsonBuilder::Success(['id'=>$id])
            : JsonBuilder::Error($result->getMessage());


    }

}

