<?php


namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\School\CourseRequest;
use App\Utils\JsonBuilder;

class CourseWareController extends Controller
{

    /**
     * 课件
     * @param CourseRequest $request
     * @return string
     */
    public function index(CourseRequest $request)
    {
        // todo ::教师端课件
        return JsonBuilder::Success();
    }

    /**
     * 收藏列表
     * @param CourseRequest $request
     * @return string
     */
    public function collectionList(CourseRequest $request)
    {
        $data = [
            'list' => [
                [
                    'down_url' => '',
                    'id'       => '',
                    'time'     => '2019-09-21 12:30',
                    'title'    => '测试课程1'
                ],
                [
                    'down_url' => '',
                    'id'       => '',
                    'time'     => '2019-09-22 12:30',
                    'title'    => '测试课程2'
                ],
                [
                    'down_url' => '',
                    'id'       => '',
                    'time'     => '2019-09-23 12:30',
                    'title'    => '测试课程3'
                ]
            ]
        ];

        return JsonBuilder::Success($data);
    }

    /**
     * 课件信息
     * @param CourseRequest $request
     * @return string
     */
    public function courseInfo(CourseRequest $request)
    {
        $data = [
            'high_list' => [
                [
                    'collect_count'  => 10,
                    'collect_status' => 1,
                    'down_count'     => 0,
                    'id'             => 1,
                    'time'           => '2019-09-20 08:30',
                    'title'          => '课件一'
                ],
                [
                    'collect_count'  => 10,
                    'collect_status' => 1,
                    'down_count'     => 0,
                    'id'             => 1,
                    'time'           => '2019-09-22 11:31',
                    'title'          => '课件二'
                ],
                [
                    'collect_count'  => 10,
                    'collect_status' => 1,
                    'down_count'     => 0,
                    'id'             => 1,
                    'time'           => '2019-09-21 12:30',
                    'title'          => '课件三'
                ]
            ],
            'type_list' => [
                [
                    'id'  => '12',
                    'img' => '',
                    '哲学',
                ],
                [
                    'id'  => '13',
                    'img' => '',
                    '法学'  => 'http://q2zxaeiiu.bkt.clouddn.com/blog/20191224170910.png'
                ],
                [
                    'id'  => '12',
                    'img' => '',
                    '教育学' => 'http://q2zxaeiiu.bkt.clouddn.com/blog/20191224170908.png',
                ],
                [
                    'id'  => '12',
                    'img' => '',
                    '经济学' => 'http://q2zxaeiiu.bkt.clouddn.com/blog/20191224170909.png',
                ],
                [
                    'id'  => '12',
                    'img' => '',
                    '历史学' => 'http://q2zxaeiiu.bkt.clouddn.com/blog/20191224170911.png',
                ],
                [
                    'id'  => '12',
                    'img' => '',
                    '文学'  => 'http://q2zxaeiiu.bkt.clouddn.com/blog/20191224170907.png',
                ],
            ]
        ];

        return JsonBuilder::Success($data);
    }

    /**
     * 上课资料列表
     * @param CourseRequest $request
     * @return string
     */
    public function courseWareData(CourseRequest $request)
    {
        $data = [
            'list' => [
                [
                    'course_data_list' => [
                        [
                            "down_url"  => "下载路径",
                            "id"        => "10",
                            "share_url" => "分享路径",
                            "time"      => "2019-09-21 10:30",
                            "title"     => "测试课件一"
                        ],
                        [
                            "down_url"  => "下载路径",
                            "id"        => "10",
                            "share_url" => "分享路径",
                            "time"      => "2019-09-22 09:30",
                            "title"     => "测试课件二"
                        ],
                        [
                            "down_url"  => "下载路径",
                            "id"        => "10",
                            "share_url" => "分享路径",
                            "time"      => "2019-11-20 12:34",
                            "title"     => "测试课件三"
                        ],
                        [
                            "down_url"  => "下载路径",
                            "id"        => "10",
                            "share_url" => "分享路径",
                            "time"      => "2019-12-20 13:30",
                            "title"     => "测试课件四"
                        ],

                    ],
                    'name'             => '赵丽颖',
                ],
                [
                    'course_data_list' => [
                        [
                            "down_url"  => "下载路径",
                            "id"        => "10",
                            "share_url" => "分享路径",
                            "time"      => "2019-09-21 10:30",
                            "title"     => "测试课件一"
                        ],
                        [
                            "down_url"  => "下载路径",
                            "id"        => "10",
                            "share_url" => "分享路径",
                            "time"      => "2019-09-22 09:30",
                            "title"     => "测试课件二"
                        ],
                        [
                            "down_url"  => "下载路径",
                            "id"        => "10",
                            "share_url" => "分享路径",
                            "time"      => "2019-11-20 12:34",
                            "title"     => "测试课件三"
                        ],
                        [
                            "down_url"  => "下载路径",
                            "id"        => "10",
                            "share_url" => "分享路径",
                            "time"      => "2019-12-20 13:30",
                            "title"     => "测试课件四"
                        ],

                    ],
                    'name'             => '赵丽颖',
                ],

            ],
        ];
        return JsonBuilder::Success($data);
    }

    /**
     * 课件列表
     * @param CourseRequest $request
     * @return string
     */
    public function courseWareList(CourseRequest $request)
    {
        $data = [
            'list' => [
                [
                    "collect_count"  => "20",//收藏人数
                    "collect_status" => "1",//是否收藏,1--收藏 0--未收藏
                    "down_count"     => "10",//下载人数
                    "down_url"       => "下载路径",
                    "id"             => "10", //优质课件id
                    "time"           => "2019-09-20 12:30",
                    "title"          => "测试课件"
                ],
                [
                    "collect_count"  => "20",//收藏人数
                    "collect_status" => "1",//是否收藏,1--收藏 0--未收藏
                    "down_count"     => "10",//下载人数
                    "down_url"       => "下载路径",
                    "id"             => "10", //优质课件id
                    "time"           => "2019-09-20 12:30",
                    "title"          => "测试课件一"
                ],
                [
                    "collect_count"  => "20",//收藏人数
                    "collect_status" => "1",//是否收藏,1--收藏 0--未收藏
                    "down_count"     => "10",//下载人数
                    "down_url"       => "下载路径",
                    "id"             => "10", //优质课件id
                    "time"           => "2019-09-20 12:30",
                    "title"          => "测试课件二"
                ]
            ]
        ];
        return JsonBuilder::Success($data);
    }


}
