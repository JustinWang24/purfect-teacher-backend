<?php


namespace App\Http\Controllers\Operator\Evaluate;


use App\Dao\Evaluate\EvaluateTeacherDao;
use App\Dao\Schools\GradeDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Dao\Users\GradeUserDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Evaluate\EvaluateTeacherRequest;
use App\Models\Evaluate\EvaluateTeacher;

class EvaluateTeacherController extends Controller
{
    public function list() {

        $this->dataForView['list'] = '';
        return view('school_manager.evaluate.evaluate_teacher.list',$this->dataForView);
    }

    public function create(EvaluateTeacherRequest $request) {
        $data = $request->getFormDate();
        // 请选择学生
        if(empty($data['user_id'])) {
            // todo  请选择学生
        }
        $schoolId = $request->getSchoolId();
        // 查询当前班级的代课老师
        $itemDao = new TimetableItemDao();
        $teachers = $itemDao->getItemByGradeId($data['grade_id'],$data['year'],$data['term']);
//        dd($teachers->toArray());
        $dao = new EvaluateTeacherDao();
        $result = $dao->create($teachers, $data['year'], $data['term'], $schoolId, $data['user_id']);
        dd($result);
    }


    public function grade(EvaluateTeacherRequest $request) {
        $schoolId = $request->getSchoolId();
        $dao = new GradeDao($request->user());
        $this->dataForView['grades'] = $dao->getBySchool($schoolId);
        $this->dataForView['pageTitle'] = '班级管理';
        return view('school_manager.evaluate.evaluate_teacher.grades', $this->dataForView);
    }


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
