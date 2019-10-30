<?php

namespace App\Http\Controllers\Teacher;

use Carbon\Carbon;
use App\Utils\JsonBuilder;
use App\Models\Schools\Room;
use App\Dao\Schools\RoomDao;
use App\Dao\Teachers\ExamDao;
use App\Dao\Courses\CourseDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\ExamRequest;

class ExamController extends Controller
{

    /**
     * 考试列表
     */
    public function index()
    {
        return view('teacher.exam.index', $this->dataForView);
    }



    public function add()
    {
        $dao = new ExamDao();
        // 获取考试类别和考试形式
        $return = $dao->getTypeAndFormalism();
        $this->dataForView['type'] = $return['type'];
        $this->dataForView['formalism'] = $return['formalism'];
        return view('teacher.exam.add', $this->dataForView);

    }




    public function create(ExamRequest $request)
    {
        $all = $request->all();

        $all['school_id'] = $request->session()->get('school.id');
        $dt = Carbon::parse($all['from']);

        $all['year'] = $dt->year;
        $all['month'] = $dt->month;
        $all['week'] = $dt->week;
        $all['day'] = $dt->day;


        $examDao = new ExamDao();
        $re = $examDao->create($all);

        if($re->id) {
            return JsonBuilder::Success([],'创建成功');
        } else {
            return JsonBuilder::Error('创建失败');
        }
    }


    /**
     * 获取教室
     * @param ExamRequest $request
     * @return string
     */
    public function getClassRooms(ExamRequest $request)
    {
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
    public function getCourses(ExamRequest $request)
    {
        $schoolId = $request->getSchoolId();
        $dao = new CourseDao();
        $list = $dao->getCoursesBySchoolId($schoolId);
        return JsonBuilder::Success($list,'请求成功');
    }


    public function data(ExamRequest $request)
    {
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

