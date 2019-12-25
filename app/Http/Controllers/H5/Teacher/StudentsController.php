<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 23/12/19
 * Time: 2:08 PM
 */

namespace App\Http\Controllers\H5\Teacher;
use App\Dao\Schools\GradeDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Dao\Users\GradeUserDao;
use App\Http\Controllers\Controller;
use App\Models\Schools\Grade;
use App\Models\Schools\GradeManager;
use App\Models\Schools\SchoolConfiguration;
use App\Models\Teachers\Teacher;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    public function my_students(Request $request){
        // 教师访问此页面的时候, 要根据课程表的安排, 找到和自己有关联的班级
        $this->dataForView['teacher'] = $request->user('api');
        $this->dataForView['api_token'] = $request->get('api_token');
        return view('h5_apps.teacher.management.students', $this->dataForView);
    }

    public function grades(Request $request){
        // 教师访问此页面的时候, 要根据课程表的安排, 找到和自己有关联的班级

        $teacher = $request->user('api');
        $schoolId = $teacher->getSchoolId();
        $this->dataForView['teacher'] = $teacher;
        $this->dataForView['api_token'] = $request->get('api_token');

        $dao = new TimetableItemDao();
        $schoolDao = new SchoolDao();
        $school = $schoolDao->getSchoolById($schoolId);
        // 同时, 作为普通老师所需要提取的数据
        /**
         * @var SchoolConfiguration $config
         */
        $config = $school->configuration;
        $startDate = $config->getTermStartDate();
        $year = $startDate->year;
        $term = $config->guessTerm(now()->month);
        $items = $dao->getItemsByTeacherForApp($teacher->id, $year, $term);

        $gradeIds = [];
        foreach($items as $item){
            if(!in_array($item->grade_id, $gradeIds)){
                $gradeIds[] = $item->grade_id;
            }
        }
        $this->dataForView['year'] = $year;
        $this->dataForView['term'] = $term;


        // 要判断当前老师的身份
        $duties = Teacher::getTeacherAllDuties($teacher->id);
        $gs = [];
        $directs = [];

        if($duties['myYearManger']){
            // 是年级组长 把该年级所有的班都列出来
            $rows = (new GradeDao())->getBySchoolAndYearForApp($schoolId, $duties['myYearManger']->year);
            foreach ($rows as $row) {
                $row->isYearManager = true;
                $gs[] = $row;
            }
        }
        elseif($duties['gradeManger']){
            // 是班主任 把作为班主任负责的班级找出来
            $g = (new GradeDao())->getGradeById( $duties['gradeManger']->id );
            $g->isGradeAdviser = true;
            $directs[] = $g;
        }

        $this->dataForView['grades'] = empty($gradeIds) ? [] : Grade::select(['id','name','year'])->whereIn('id',$gradeIds)->get();
        $this->dataForView['grades'] = array_merge($directs, empty($gradeIds) ? [] : $this->dataForView['grades']->toArray());
        $this->dataForView['grades'] = array_merge($gs, $this->dataForView['grades']);
        return view('h5_apps.teacher.management.grades', $this->dataForView);
    }

    public function students_view(Request $request){
        $teacher = $request->user('api');
        $schoolId = $teacher->getSchoolId();
        $this->dataForView['teacher'] = $teacher;
        $this->dataForView['api_token'] = $request->get('api_token');

        $grade = (new GradeDao())->getGradeById($request->get('grade'));
        $students = (new GradeUserDao())->getByGradeForApp($request->get('grade'));
        $this->dataForView['students'] = $students;
        $this->dataForView['grade'] = $grade;
        return view('h5_apps.teacher.management.students', $this->dataForView);
    }
}