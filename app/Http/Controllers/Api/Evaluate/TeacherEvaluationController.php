<?php

namespace App\Http\Controllers\Api\Evaluate;

use App\Dao\Schools\GradeDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Dao\Users\GradeUserDao;
use App\Http\Controllers\Controller;
use App\Models\Acl\Role;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;

class TeacherEvaluationController extends Controller
{

    /**
     * 教师评价班级列表
     * @param Request $request
     * @return string
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $dao  = new TimetableItemDao;

        $grade = $dao->getTeacherTeachingGrade($user->id);

        $gradeIds = [];
        foreach ($grade as $key => $val) {
            $gradeIds[] = $val->grade_id;
        }

        $gradeIds = array_unique($gradeIds);

        $gradeDao = new GradeDao;
        $grades   = $gradeDao->getGrades($gradeIds);

        $data = [];
        foreach ($grades as $k => $v) {
            $data[$k]['grade_id'] = $v->id;
            $data[$k]['name']     = $v->name;
        }

        return JsonBuilder::Success($data);
    }

    /**
     * 班级所有学生
     * @param Request $request
     * @return string
     */
    public function student(Request $request)
    {
        $gradeId  = $request->get('grade_id');

        $gradeUserDao = new GradeUserDao;
        $users = $gradeUserDao->paginateUserByGrade($gradeId, Role::VERIFIED_USER_STUDENT);
        $data = [];

        foreach ($users as $key => $val) {
            $data[$key]['user_id'] = $val->user_id;
            $data[$key]['name'] = $val->name;
        }

       return JsonBuilder::Success($data);
    }

    /**
     * 评教模板
     * @param Request $request
     */
    public function template(Request $request)
    {

    }

}
