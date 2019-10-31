<?php

namespace App\Http\Controllers\Teacher;

use App\Dao\Schools\DepartmentDao;
use App\Dao\Schools\GradeDao;
use App\Dao\Schools\MajorDao;
use Carbon\Carbon;
use App\Utils\JsonBuilder;
use App\Models\Schools\Room;
use App\Dao\Schools\RoomDao;
use App\Dao\Teachers\ExamDao;
use App\Dao\Courses\CourseDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\ExamRequest;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{

    /**
     * 考试列表
     */
    public function index() {
        return view('teacher.exam.index', $this->dataForView);
    }


    /**
     * 添加页面
     */
    public function add() {
        $dao = new ExamDao();
        // 获取考试类别和考试形式
        $return = $dao->getTypeAndFormalism();
        $this->dataForView['type'] = $return['type'];
        $this->dataForView['formalism'] = $return['formalism'];
        return view('teacher.exam.add', $this->dataForView);
    }


    /**
     * 创建考试
     * @param ExamRequest $request
     * @return string
     */
    public function create(ExamRequest $request) {
        $all = $request->all();
        $all['school_id'] = $request->session()->get('school.id');

        $examDao = new ExamDao();
        $re = $examDao->create($all);

        if($re->id) {
            return JsonBuilder::Success([],'创建成功');
        } else {
            return JsonBuilder::Error('创建失败');
        }
    }

    /**
     * 获取系列表
     * @param ExamRequest $request
     * @return string
     */
    public function getDepartmentList(ExamRequest $request) {
        $schoolId = $request->session()->get('school.id');
        $departmentDao = new DepartmentDao($request->user());
        $field = ['id' ,'school_id', 'name', 'institute_id', 'campuses_id'];
        $list = $departmentDao->getDepartmentBySchoolId($schoolId,$field)->toArray();
        return JsonBuilder::Success($list,'请求成功');
    }

    /**
     * 获取系下面的专业
     * @param ExamRequest $request
     * @return string
     */
    public function getMajorList(ExamRequest $request) {
        $departmentId = $request->get('department_id');
        $majorDao = new MajorDao();
        $field = ['id', 'name','school_id'];
        $list = $majorDao->getByDepartment($departmentId,$field)->toArray();
        return JsonBuilder::Success($list,'请求成功');
    }

     /**
     * 获取专业下的班
     * @param ExamRequest $request
     * @return string
     */
    public function getGradeList(ExamRequest $request) {
        $majorId = $request->get('major_id');
        $year = $request->get('year');
        $gradeDao = new GradeDao($request->user());
        $field = ['id', 'name', 'year'];
        $list = $gradeDao->getGradesByMajorAndYear($majorId,$year,$field)->toArray();
        return JsonBuilder::Success($list,'请求成功');
    }


    /**
     * 创建考试计划
     * @param ExamRequest $request
     * @return string
     */
    public function createExamPlan(ExamRequest $request) {
        $all = $request->all();
        $examDao = new ExamDao();

        $re = $examDao->createPlan($all);
        if($re->id) {
            return JsonBuilder::Success('创建成功');
        } else {
            return JsonBuilder::Error('创建失败');
        }
    }


    /**
     * 获取当前时间没有考试的教室
     * @param ExamRequest $request
     * @return string
     */
    public function getLeisureRoom(ExamRequest $request) {

        $campusId = $request->get('campus_id');
        $from = $request->get('from');
        $to = $request->get('to');
        $user = $request->user();
        $examDao = new ExamDao();
        $result = $examDao->getLeisureRoom($campusId,$from,$to,$user);

        return JsonBuilder::Success($result,'请求成功');
    }


    /**
     * 创建考点
     * @param ExamRequest $request
     * @return string
     */
    public function createPlanRoom(ExamRequest $request) {
        $roomIdArr = $request->get('room_id');
        $planId = $request->get('plan_id');
        $user = $request->user();
        // 查询考试计划
        $examDao = new ExamDao();
        $result  = $examDao->createPlanRoom($roomIdArr, $planId, $user);
        if($result['code'] == 0)
        {
            return JsonBuilder::Error($result['msg']);
        }
        else
        {
            return JsonBuilder::Success('创建成功');
        }
    }


    /**
     * 考点绑定监考老师
     * @param ExamRequest $request
     * @return string
     */
    public function roomBindingTeacher(ExamRequest $request) {
        $all = $request->all();

        $examDao = new ExamDao();
        $result = $examDao->roomBindingTeacher($all);
        if($result['code'] == 1) {
            return JsonBuilder::Success($result['msg']);
        } else {
            return JsonBuilder::Error($result['msg']);
        }
    }






    /**
     * 获取教室
     * @param ExamRequest $request
     * @return string
     */
    public function getClassRooms(ExamRequest $request) {
        $dao = new RoomDao($request->user());
        $schoolId = $request->getSchoolId();
        $map = ['school_id'=>$schoolId,'type'=>Room::TYPE_CLASSROOM];
        $list = $dao->getRooms($map)->toArray();
        return JsonBuilder::Success($list,'请求成功');
    }


    /**
     * 获取课程列表
     * @param ExamRequest $request
     * @return string
     */
    public function getCourses(ExamRequest $request) {
        $schoolId = $request->getSchoolId();
        $dao = new CourseDao();
        $list = $dao->getCoursesBySchoolId($schoolId);
        return JsonBuilder::Success($list,'请求成功');
    }


    public function data(ExamRequest $request) {
        $schoolId = $request->getSchoolId();
        $dao = new ExamDao();
        $map = ['school_id'=>$schoolId];

        $list = $dao->getExams($map);
        $dt = Carbon::parse();

        foreach ($list as $key => $val) {
            $list[$key]['type_text'] = $val->TypeText;
            $list[$key]['formalism_text'] = $val->FormalismText;

            if($dt->lt($val['exam_time'].' '.$val['from'])) {
                // 判断当前时间是否小于开始时间
                $list[$key]['status'] = 0;
                $list[$key]['status_text'] = '未考试';
            } else if($dt->gte($val['exam_time'].' '.$val['to'])) {
                // 判断当前时间是否大于等于结束时间
                $list[$key]['status'] = 1;
                $list[$key]['status_text'] = '已考试';
            } else {
                // 正在考试
                $list[$key]['status'] = 2;
                $list[$key]['status_text'] = '正在考试';
            }
        }
        return JsonBuilder::Success($list->toArray(),'请求成功');
    }

}

