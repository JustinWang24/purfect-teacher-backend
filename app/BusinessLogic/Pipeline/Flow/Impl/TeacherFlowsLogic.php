<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 4/12/19
 * Time: 8:49 PM
 */

namespace App\BusinessLogic\Pipeline\Flow\Impl;

use App\Dao\Pipeline\FlowDao;
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
            'name'=>IFlow::TYPE_STUDENT_ONLY_TXT,
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

        $result =  $dao->getGroupedFlows(
            $this->user->getSchoolId(), Teacher::FlowTypes(), $forApp
        );

        $types = array_merge($types, $result);
        return $types;
    }
}