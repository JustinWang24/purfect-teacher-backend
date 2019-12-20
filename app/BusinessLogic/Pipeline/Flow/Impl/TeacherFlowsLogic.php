<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 4/12/19
 * Time: 8:49 PM
 */

namespace App\BusinessLogic\Pipeline\Flow\Impl;

use App\Dao\Pipeline\FlowDao;
use App\Models\Schools\News;
use App\Models\Teachers\Teacher;
use App\User;
use App\Utils\Pipeline\IFlow;

class TeacherFlowsLogic extends GeneralFlowsLogic
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * 获取教师的专用流程
     * @param $forApp
     * @return IFlow[]|array
     */
    public function getMyFlows($forApp = false)
    {
        $dao = new FlowDao();
        $types[] = [
            'name'=>IFlow::TYPE_TEACHER_ONLY_TXT,
            'key'=>1000,
            'flows'=>[
                [
                    'id'=>-1,
                    'name'=>'签到',
                    'icon'=>asset('assets/img/pipeline/icon1@2x.png')
                ],
                [
                    'id'=>-2,
                    'name'=>'项目',
                    'icon'=>asset('assets/img/pipeline/icon2@2x.png')
                ],
                [
                    'id'=>-3,
                    'name'=>'发布任务',
                    'icon'=>asset('assets/img/pipeline/icon3@2x.png')
                ]
            ]
        ];

        // 特殊的 直接加载 H5

        $types[] = [
            'name'=>'我的校园',
            'key'=>1010,
            'flows'=>[
                [
                    'id'=>News::TYPE_SCIENCE,
                    'name'=>News::TYPE_SCIENCE_TXT,
                    'icon'=>asset('assets/img/pipeline/icon1@2x.png')
                ],
                [
                    'id'=>News::TYPE_CAMPUS,
                    'name'=>News::TYPE_CAMPUS_TXT,
                    'icon'=>asset('assets/img/pipeline/icon2@2x.png')
                ],
                [
                    'id'=>News::TYPE_NEWS,
                    'name'=>News::TYPE_NEWS_TXT,
                    'icon'=>asset('assets/img/pipeline/icon3@2x.png')
                ],
                [
                    'id'=>-4,
                    'name'=>'通知公告',
                    'icon'=>asset('assets/img/pipeline/icon3@2x.png')
                ],
                [
                    'id'=>-5,
                    'name'=>'校历',
                    'icon'=>asset('assets/img/pipeline/icon3@2x.png')
                ],
                [
                    'id'=>-6,
                    'name'=>'通讯录',
                    'icon'=>asset('assets/img/pipeline/icon3@2x.png')
                ],
                [
                    'id'=>-7,
                    'name'=>'作息时间',
                    'icon'=>asset('assets/img/pipeline/icon3@2x.png')
                ],
                [
                    'id'=>-8,
                    'name'=>'值班',
                    'icon'=>asset('assets/img/pipeline/icon3@2x.png')
                ],
            ]
        ];

        $result =  $dao->getGroupedFlows(
            $this->user->getSchoolId(), Teacher::FlowTypes(), $forApp
        );

        $types = array_merge($types, $result);
        return $types;
    }
}