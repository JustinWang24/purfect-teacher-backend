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
}