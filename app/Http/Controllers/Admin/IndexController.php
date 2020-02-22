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
     * PC 助手页
     * @param MyStandardRequest $request
     * @return string
     */
    public function helpIcon(MyStandardRequest $request)
    {
        $data = [
            ['name' => '教学助手', 'helper_page' =>
               [
                    [ 'name'=> '课表', 'icon' => asset('assets/img/teacher/ass-icon3.png') ],
                    [ 'name'=> '教学资料', 'icon' => asset('assets/img/teacher/ass-icon3.png') ],
                    [ 'name'=> '签到', 'icon' => asset('assets/img/teacher/ass-icon3.png') ],
                    [ 'name'=> '评分', 'icon' => asset('assets/img/teacher/ass-icon3.png') ],
                    [ 'name'=> '选课', 'icon' => asset('assets/img/teacher/ass-icon3.png') ],
               ]
            ],
            ['name' => '班主任助手', 'helper_page' =>
               [
                    [ 'name'=> '班级管理', 'icon' => asset('assets/img/teacher/ass-icon3.png') ],
                    [ 'name'=> '学生信息', 'icon' => asset('assets/img/teacher/ass-icon3.png') ],
                    [ 'name'=> '班级签到', 'icon' => asset('assets/img/teacher/ass-icon3.png') ],
                    [ 'name'=> '班级评分', 'icon' => asset('assets/img/teacher/ass-icon3.png') ],
               ]
            ]
        ];

        return JsonBuilder::Success($data);

    }
}
