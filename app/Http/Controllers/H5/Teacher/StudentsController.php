<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 23/12/19
 * Time: 2:08 PM
 */

namespace App\Http\Controllers\H5\Teacher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    public function my_students(Request $request){
        // 教师访问此页面的时候, 要根据课程表的安排, 找到和自己有关联的班级
        $this->dataForView['teacher'] = $request->user('api');
        $this->dataForView['api_token'] = $request->get('api_token');
        return view('h5_apps.teacher.management.students', $this->dataForView);
    }
}