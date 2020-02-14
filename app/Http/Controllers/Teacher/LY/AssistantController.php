<?php
/**
 * Created by https://yue.dev
 * Author: Justin Wang
 * Email: hi@yue.dev
 */

namespace App\Http\Controllers\Teacher\LY;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    /**
     * 首页/助手 页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        return view('teacher.ly.assistant.index',$this->dataForView);
    }

    public function evaluation(Request $request){
        return view('teacher.ly.assistant.evaluation',$this->dataForView);
    }

    public function check_in(Request $request){
        return view('teacher.ly.assistant.check_in',$this->dataForView);
    }

    public function electives(Request $request){
        return view('teacher.ly.assistant.electives',$this->dataForView);
    }

    public function grades_manager(Request $request){
        return view('teacher.ly.assistant.grades_manager',$this->dataForView);
    }

    public function students_manager(Request $request){
        return view('teacher.ly.assistant.students_manager',$this->dataForView);
    }

    public function grades_check_in(Request $request){
        return view('teacher.ly.assistant.grades_check_in',$this->dataForView);
    }

    public function grades_evaluations(Request $request){
        return view('teacher.ly.assistant.grades_evaluations',$this->dataForView);
    }
}