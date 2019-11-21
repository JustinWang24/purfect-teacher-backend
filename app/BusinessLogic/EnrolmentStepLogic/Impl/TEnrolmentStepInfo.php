<?php


namespace App\BusinessLogic\EnrolmentStepLogic\Impl;


use App\Models\EnrolmentStep\SchoolEnrolmentStep;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use App\Dao\EnrolmentStep\SchoolEnrolmentStepDao;

trait TEnrolmentStepInfo
{

    /**
     * @param  SchoolEnrolmentStep $step
     * @return MessageBag
     */
    protected function getEnrolmentStepInfo($step) {

        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);


        $step->campus->name;

        $step->user;   // 负责人
        $step->tasks;  // 步骤子类
        // 协助人
        $assists = $step->assists;
        foreach ($assists as $k=>$v) {
            $assists[$k] = $v->user;
        }

        // 文件
        $medias = $step->medias;
        foreach ($medias as $k => $v) {
            $medias[$k] = $v->media;
        }

        $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
        $messageBag->setData($step);
        return $messageBag;
    }
}
