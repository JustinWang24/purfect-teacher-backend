<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/1/20
 * Time: 4:33 PM
 */

namespace App\Http\Controllers\Teacher\Course;
use App\Dao\Courses\CourseDao;
use App\Dao\Courses\Lectures\LectureDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Dao\Users\UserDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\MaterialRequest;
use App\Models\Course;
use App\Models\Courses\CourseTeacher;
use App\Models\Courses\TeachingLog;
use App\Utils\CourseHelpers\IndexerHelper;
use App\Utils\JsonBuilder;
use Psy\Util\Json;

class MaterialsController extends Controller
{
    /**
     * @param MaterialRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manager(MaterialRequest $request){
        $this->dataForView['pageTitle'] = '课件管理';
        $this->dataForView['redactor'] = true;          // 让框架帮助你自动插入导入 redactor 的 css 和 js 语句
        $this->dataForView['redactorWithVueJs'] = true; // 让框架帮助你自动插入导入 redactor 的组件的语句

        $courseDao = new CourseDao();
        /**
         * @var Course $course
         */
        $course = $courseDao->getCourseById($request->getCourseId());
        $teacher = (new UserDao())->getUserById($request->getTeacherId());
        $this->dataForView['course'] = $course;
        $this->dataForView['teacher'] = $teacher;

        // 当前课程的授课班级集合
        $timetableItemDao = new TimetableItemDao();
        $items = $timetableItemDao->getGradesByCourseAndTeacher($request->getCourseId(), $request->getTeacherId());
        $grades = [];
        foreach ($items as $item) {
            $grades[] = [
                'id'=>$item->grade->id,
                'name'=>$item->grade->name,
            ];
        }
        $this->dataForView['grades'] = $grades;

        return view('teacher.course.materials.manager', $this->dataForView);
    }

    /**
     * @param MaterialRequest $request
     * @return string
     */
    public function load_teacher_note(MaterialRequest $request){
        $courseDao = new CourseDao();
        /**
         * @var Course $course
         */
        $course = $courseDao->getCourseById($request->getCourseId());
        $courseTeacher = $course->getCourseTeacher($request->getTeacherId());

        // 教学日志
        $logs = $courseDao->getTeachingLogs($request->getCourseId(), $request->getTeacherId(),$request->get('skip',0));
        return JsonBuilder::Success(['note'=>$courseTeacher,'logs'=>$logs]);
    }

    /**
     * 保存课程简介与教学计划
     * @param MaterialRequest $request
     * @return string
     */
    public function save_teacher_note(MaterialRequest $request){
        $notesData = $request->get('notes');
        $id = $notesData['id'];
        CourseTeacher::where('id',$id)->update($notesData);
        return JsonBuilder::Success();
    }

    /**
     * 保存教学日志
     * @param MaterialRequest $request
     * @return string
     */
    public function save_log(MaterialRequest $request){
        $logData = $request->get('log');
        $dao = new CourseDao();
        if(empty($logData['id'])){
            $logData['course_id'] = $request->getCourseId();
            $logData['teacher_id'] = $request->getTeacherId();
        }
        $log = $dao->saveTeachingLog($logData);
        return JsonBuilder::Success(['id'=>$log->id]);
    }

    /**
     * @param MaterialRequest $request
     * @return string
     */
    public function create(MaterialRequest $request){
        $dao = new LectureDao();
        $msg = $dao->saveLectureMaterial($request->get('material'));
        return $msg->isSuccess() ? JsonBuilder::Success(['material'=>$msg->getData()]) : JsonBuilder::Error($msg->getMessage());
    }

    /**
     * @param MaterialRequest $request
     * @return string
     */
    public function load(MaterialRequest $request){
        $dao = new LectureDao();
        $material = $dao->getLectureMaterial($request->get('id'));
        return JsonBuilder::Success(['material'=>$material]);
    }

    /**
     * @param MaterialRequest $request
     * @return string
     */
    public function delete(MaterialRequest $request){
        $dao = new CourseDao();
        $deleted = $dao->deleteCourseMaterial($request->get('id'));
        return $deleted ? JsonBuilder::Success() : JsonBuilder::Error();
    }

    /**
     * 加载指定课节的课件
     * @param MaterialRequest $request
     * @return string
     */
    public function load_lecture(MaterialRequest $request){
        $dao = new LectureDao();
        $materials = $dao->getLecturesByCourseAndTeacherAndIndex(
            $request->get('course'),
            $request->get('teacher'),
            $request->get('index')
        );
        return JsonBuilder::Success(['lecture'=>$materials]);
    }

    /**
     * 更新课件的记录，注意这个方法只会更新title和summary这两个字段
     * @param MaterialRequest $request
     * @return string
     */
    public function save_lecture(MaterialRequest $request){
        $dao = new LectureDao();
        $dao->updateLectureSummary($request->get('lecture'));
        return JsonBuilder::Success();
    }

    /**
     * 加载某个课节的材料
     * @param MaterialRequest $request
     * @return string
     */
    public function load_lecture_materials(MaterialRequest $request){
        $dao = new LectureDao();
        return JsonBuilder::Success(['materials'=>$dao->getLectureMaterials($request->get('lecture_id'))]);
    }

    /**
     * 加载某课节的作业数据
     * @param MaterialRequest $request
     * @return string
     */
    public function load_lecture_homeworks(MaterialRequest $request){
        $dao = new LectureDao();
        return JsonBuilder::Success(
            ['homeworks'=>$dao->getLectureHomework($request->get('lecture_id'),$request->get('grades'))]
        );
    }
}