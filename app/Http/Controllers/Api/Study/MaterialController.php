<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/3/9
 * Time: 上午11:19
 */

namespace App\Http\Controllers\Api\Study;


use App\Dao\Courses\CourseDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
use App\Dao\Courses\CourseTeacherDao;
use App\Dao\Courses\Lectures\LectureDao;
use App\Http\Requests\Course\MaterialRequest;

class MaterialController extends Controller
{


    /**
     * 我的课程
     * @param MaterialRequest $request
     * @return string
     */
    public function courses(MaterialRequest $request) {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $courseTeacherDao = new CourseTeacherDao();
        $courseTeacher = $courseTeacherDao->getCoursesByTeacher($user->id);
        if(count($courseTeacher) == 0) {
            return JsonBuilder::Success('您没有课程');
        }
        $lectureDao = new LectureDao();
        $types = $lectureDao->getMaterialType($schoolId);

        foreach ($types as $key => $val) {
            $num = $lectureDao->getMaterialNumByUserAndType($user->id, $val->type_id);
            $val->num = $num;
        }
        $courses = [];
        foreach ($courseTeacher as $key => $item) {
            $course = $item->course;
            $courses[] = [
                'course_id' => $course->id,
                'course_name' => $course->name,
                'duration' => $course->duration,
                'desc' => $course->desc,
                'types' => $types
            ];
        }

        return JsonBuilder::Success($courses);
    }


    /**
     * 类型课程资料
     * @param MaterialRequest $request
     * @return string
     */
    public function materialsByType(MaterialRequest $request) {
        $user = $request->user();
        $typeId = $request->getType();
        $courseId = $request->getCourseId();
        if(is_null($typeId) || is_null($courseId)) {
            return JsonBuilder::Error('缺少参数');
        }

        $lectureDao = new LectureDao();

        $return = $lectureDao->getMaterialByCourseId($courseId, $typeId, $user->id, false);
        $result = [];
        foreach ($return as $key => $item) {
            $idx = $item->lecture->idx;
            $result[] = [
                'desc' => $item->description,
                'url' => $item->url,
                'lecture' => '第'.$idx.'节',
            ];
        }
        return JsonBuilder::Success($result);
    }


    /**
     * 课程详情
     * @param MaterialRequest $request
     * @return string
     */
    public function courseDetails(MaterialRequest $request) {
        $courseId = $request->getCourseId();
        if(is_null($courseId)) {
            return JsonBuilder::Error('缺少参数');
        }
        $dao = new CourseDao();
        $course = $dao->getCourseById($courseId);
        $result = [
            'course_id' => $course->id,
            'course_name' => $course->name,
            'duration' => $course->duration,
            'desc' => $course->desc,
        ];

        return JsonBuilder::Success($result);
    }


    /**
     * 编辑课程详情
     * @param MaterialRequest $request
     * @return string
     */
    public function saveCourse(MaterialRequest $request) {
        $courseId = $request->getCourseId();
        $desc = $request->get('desc');
        if(is_null($courseId) || is_null($desc)) {
            return JsonBuilder::Error('缺少参数');
        }
        $dao = new CourseDao();
        $result = $dao->updateCourseDescByCourseId($courseId, $desc);
        if($result) {
            return JsonBuilder::Success('编辑成功');
        } else {
            return JsonBuilder::Error('编辑失败');
        }

    }


    /**
     * 课程班级列表和课节列表
     * @param MaterialRequest $request
     * @return string
     */
    public function getCourseGradeList(MaterialRequest $request) {
        $user = $request->user();
        $courseId = $request->getCourseId();
        if(is_null($courseId)) {
            return JsonBuilder::Error('缺少参数');
        }
        $timeTableDao = new TimetableItemDao();
        $gradeIds = $timeTableDao->getGradeListByCourseIdAndTeacherId($courseId, $user->id);
        if(count($gradeIds) == 0) {
            return JsonBuilder::Success('当前课程没有代理班级');
        }

        $courseDao = new CourseDao();
        $course = $courseDao->getCourseById($courseId);

        $grades = [];
        foreach ($gradeIds as $key => $item) {
            $grade = $item->grade;
            $grades[] = [
                'grade_id' => $grade->id,
                'grade_name' => $grade->name,
            ];
        }

        $duration = [];
        for ($i=1; $i<=$course->duration; $i++) {
            $duration[] = [
                'idx' => $i,
                'name' => '第'.$i.'节',
            ];
        }

        $data = [
            'grades' => $grades,
            'durations' => $duration,
        ];

        return JsonBuilder::Success($data);



    }



    public function materials(MaterialRequest $request) {
        $user = $request->user();
    }

}