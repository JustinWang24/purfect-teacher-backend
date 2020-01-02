<?php


namespace App\Http\Controllers\Operator\Evaluate;


use App\Dao\Schools\GradeDao;
use App\Dao\Users\GradeUserDao;
use App\Utils\FlashMessageBuilder;
use App\Http\Controllers\Controller;
use App\Dao\Timetable\TimetableItemDao;
use App\Dao\Evaluate\EvaluateTeacherDao;
use App\Models\Evaluate\EvaluateTeacher;
use App\Http\Requests\Evaluate\EvaluateTeacherRequest;

class EvaluateTeacherController extends Controller
{
    public function list(EvaluateTeacherRequest $request) {
        $schoolId = $request->getSchoolId();
        $dao = new EvaluateTeacherDao();
        $list = $dao->getEvaluateTeacherList($schoolId);
        $this->dataForView['list'] = $list;
        return view('school_manager.evaluate.evaluate_teacher.list',$this->dataForView);
    }

    /**
     * 创建评教 选择评教学生
     * @param EvaluateTeacherRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(EvaluateTeacherRequest $request) {
        $data = $request->getFormDate();
        // 请选择学生
        if(empty($data['user_id'])) {
            FlashMessageBuilder::Push($request,FlashMessageBuilder::DANGER,'请选择学生');
            return redirect()->route('school_manager.evaluate.student-list',['grade_id'=>$data['grade_id']]);
        }
        $schoolId = $request->getSchoolId();
        // 查询当前班级的代课老师
        $itemDao = new TimetableItemDao();
        $teachers = $itemDao->getItemByGradeId($data['grade_id'],$data['year'],$data['term']);
        if(count($teachers) == 0) {
            FlashMessageBuilder::Push($request,FlashMessageBuilder::DANGER,'当前班级没有代课老师');
            return redirect()->route('school_manager.evaluate.teacher-list');
        }

        $dao = new EvaluateTeacherDao();
        $result = $dao->create($teachers, $data['year'], $data['term'], $schoolId, $data['user_id'], $data['grade_id']);
        $msg = $result->getMessage();
        if($result->isSuccess()) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,$msg);
        } else {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,$msg);
        }
        return redirect()->route('school_manager.evaluate.teacher-list');
    }


    /**
     * 班级列表
     * @param EvaluateTeacherRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function grade(EvaluateTeacherRequest $request) {
        $schoolId = $request->getSchoolId();
        $dao = new GradeDao($request->user());
        $this->dataForView['grades'] = $dao->getBySchool($schoolId);
        $this->dataForView['pageTitle'] = '班级管理';
        return view('school_manager.evaluate.evaluate_teacher.grades', $this->dataForView);
    }


    /**
     * 学生列表
     * @param EvaluateTeacherRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function student(EvaluateTeacherRequest $request) {
        $model = new EvaluateTeacher();
        $gradeId = $request->get('grade_id');
        $gradeUserDao = new GradeUserDao();
        $students = $gradeUserDao->getByGradeForApp($gradeId);
        $this->dataForView['year'] = $model->year();
        $this->dataForView['type'] = $model->allType();
        $this->dataForView['grade_id'] = $gradeId;
        $this->dataForView['students'] = $students;
        return view('school_manager.evaluate.evaluate_teacher.student', $this->dataForView);
    }

}
