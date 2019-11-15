<?php

namespace App\Events;

use App\Models\RecruitStudent\RegistrationInformatics;

interface HasRegistrationForm
{

    /**
     * 获取form
     * @return RegistrationInformatics
     */
    public function getForm(): RegistrationInformatics;


    /**
     * 获取消息类别
     * @return int
     */
    public function getMessageType() : int ;


    /**
     * 获取消息级别
     * @return int
     */
    public function getPriority() : int ;


    /**
     * 获取内容
     * @return string
     */
    public function getSystemContent() : string ;


    /**
     * 获取下一步
     * @return string
     */
    public function getNextMove() : string ;
}
