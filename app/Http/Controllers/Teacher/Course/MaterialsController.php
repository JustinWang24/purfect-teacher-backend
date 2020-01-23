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

class MaterialsController extends Controller
{

    public function manager(MaterialRequest $request){
        $this->dataForView['pageTitle'] = '课件管理';
        $this->dataForView['redactor'] = true;          // 让框架帮助你自动插入导入 redactor 的 css 和 js 语句
        $this->dataForView['redactorWithVueJs'] = true; // 让框架帮助你自动插入导入 redactor 的组件的语句
        $this->dataForView['course'] = (new CourseDao())->getCourseById($request->getCourseId());
        $this->dataForView['teacher'] = (new UserDao())->getUserById($request->getTeacherId());
        return view('teacher.course.materials.manager', $this->dataForView);
    }
}