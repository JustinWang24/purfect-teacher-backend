<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 7/12/19
 * Time: 5:07 PM
 */

namespace App\BusinessLogic\Pipeline\Flow\Impl;

use App\User;
use App\Dao\Pipeline\FlowDao;
use App\Utils\Pipeline\IFlow;

class StudentFlowsLogic extends GeneralFlowsLogic
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function getMyFlows($forApp = false)
    {
        $dao = new FlowDao();

        $types[] = [
            'name'=>IFlow::TYPE_STUDENT_ONLY_TXT,
            'key'=>1000,
            'flows'=>[
                [
                    'id'=>-1,
                    'name'=>'招生',
                    'icon'=>asset('assets/img/pipeline/icon1@2x.png')
                ],
                [
                    'id'=>-2,
                    'name'=>'迎新',
                    'icon'=>asset('assets/img/pipeline/icon2@2x.png')
                ],
                [
                    'id'=>-3,
                    'name'=>'离校',
                    'icon'=>asset('assets/img/pipeline/icon3@2x.png')
                ]
            ]
        ];

        $result =  $dao->getGroupedFlows(
            $this->user->getSchoolId(), [IFlow::TYPE_FINANCE, IFlow::TYPE_STUDENT_COMMON], $forApp
        );

        $types = array_merge($types, $result);

        // 校园助手: 这个是特殊的
        $types[] = [
            'name'=>'校园助手',
            'key'=>1000,
            'flows'=>[
                [
                    'id'=>-4,
                    'name'=>'通讯录',
                    'icon'=>asset('assets/img/pipeline/icon13@2x.png')
                ]
            ]
        ];

        return $types;
    }
}