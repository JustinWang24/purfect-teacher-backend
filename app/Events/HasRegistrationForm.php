<?php


namespace App\Events;


use App\Models\RecruitStudent\RegistrationInformatics;

interface HasRegistrationForm
{

    /**
     * 获取from
     * @return RegistrationInformatics
     */
    public function getForm(): RegistrationInformatics;


    /**
     * 获取消息类别
     * @return int
     */
    public function getMessageType() : int ;

    public function getPriority();
}
