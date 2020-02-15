<?php
/**
 * Created by https://yue.dev
 * Author: Justin Wang
 * Email: hi@yue.dev
 */

namespace App\Http\Controllers\Teacher\LY;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OaController extends Controller
{
    /**
     * 首页/消息中心 页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        return view('teacher.ly.oa.index',$this->dataForView);
    }

    public function notices_center(Request $request){
        return view('teacher.ly.oa.notices_center',$this->dataForView);
    }

    public function logs(Request $request){
        return view('teacher.ly.oa.logs',$this->dataForView);
    }

    public function internal_messages(Request $request){
        return view('teacher.ly.oa.internal_messages',$this->dataForView);
    }

    public function meetings(Request $request){
        return view('teacher.ly.oa.meetings',$this->dataForView);
    }

    public function tasks(Request $request){
        return view('teacher.ly.oa.tasks',$this->dataForView);
    }

    public function applications(Request $request){
        return view('teacher.ly.oa.applications',$this->dataForView);
    }

    public function approvals(Request $request){
        return view('teacher.ly.oa.approvals',$this->dataForView);
    }
}