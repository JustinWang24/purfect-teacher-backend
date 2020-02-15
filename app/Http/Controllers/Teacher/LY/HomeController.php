<?php
/**
 * Created by https://yue.dev
 * Author: Justin Wang
 * Email: hi@yue.dev
 */

namespace App\Http\Controllers\Teacher\LY;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * 首页/消息中心 页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function message_center(Request $request){
        return view('teacher.ly.home.message_center',$this->dataForView);
    }

    /**
     * 首页/校园新闻 页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function school_news(Request $request){
        return view('teacher.ly.home.school_news',$this->dataForView);
    }
}