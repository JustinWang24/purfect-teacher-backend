<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/1/20
 * Time: 4:33 PM
 */

namespace App\Http\Controllers\Teacher\Course;
use App\Dao\Courses\CourseDao;
use App\Dao\Users\UserDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\MaterialRequest;
use App\Models\Course;
use App\Utils\CourseHelpers\IndexerHelper;
use App\Utils\JsonBuilder;

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
        $this->dataForView['materials'] = $courseDao->getCourseMaterials($course->id, $teacher->id);
        $this->dataForView['course'] = $course;
        $this->dataForView['teacher'] = $teacher;

        return view('teacher.course.materials.manager', $this->dataForView);
    }

    /**
     * @param MaterialRequest $request
     * @return string
     */
    public function create(MaterialRequest $request){
        $dao = new CourseDao();
        $msg = $dao->saveMaterial($request->get('material'));
        return $msg->isSuccess() ? JsonBuilder::Success($msg->getData()->id) : JsonBuilder::Error($msg->getMessage());
    }

    /**
     * @param MaterialRequest $request
     * @return string
     */
    public function load(MaterialRequest $request){
        $dao = new CourseDao();
        $material = $dao->getCourseMaterial($request->get('id'));
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
}