<?php

namespace App\Utils\Misc\Contracts;

use App\Utils\ReturnData\IMessageBag;

interface ISmsSender
{
    /**
     * @param string $mobile
     * @param string $templateId
     * @param array $data
     * @return IMessageBag
     */
    public function send($mobile,$templateId,$data): IMessageBag;
}
