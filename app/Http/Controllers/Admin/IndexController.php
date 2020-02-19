<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MyStandardRequest;
use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{

    /**
     * PC 办公页面
     */
    public function officeIcon()
    {
        $data = [
            ['name' => '通知公告', 'icon' => asset('assets/img/teacher/ass-icon3.png')],
            ['name' => '日志', 'icon' => asset('assets/img/teacher/ass-icon3.png')],
            ['name' => '内部信', 'icon' => asset('assets/img/teacher/ass-icon3.png')],
            ['name' => '会议', 'icon' => asset('assets/img/teacher/ass-icon3.png')],
            ['name' => '公文', 'icon' => asset('assets/img/teacher/ass-icon3.png')],
            ['name' => '任务', 'icon' => asset('assets/img/teacher/ass-icon3.png')],
        ];

        return JsonBuilder::Success($data);
    }

    /**
     * PC 我的审批
     * @param MyStandardRequest $request
     */
    public function index(MyStandardRequest $request)
    {


    }
}
