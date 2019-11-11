<?php


namespace App\Utils\Misc\Impl;

use App\Utils\Misc\Contracts\ISmsSender;
use App\ThirdParty\YunLianSDK\REST;
use App\Utils\ReturnData\IMessageBag;

class YunLianSmsLogic implements ISmsSender
{
    //主帐号
    private $accountSid;

    //主帐号Token
    private $accountToken;

    //应用Id
    private $appId;

    //请求地址
    private $serverIP;

    //请求端口
    private $serverPort;

    //REST版本号
    private $softVersion;

    public function __construct()
    {
        $this->accountSid   = '';
        $this->accountToken = '';
        $this->appId        = '';
        $this->accountSid   = '';
        $this->softVersion  = '2013-12-26';
        $this->serverPort   = '8883';
        $this->serverIP     = 'app.cloopen.com';

    }


    public function sms()
    {
        $sms = new REST;
        dd($sms);
    }


    /**
     * @param string $mobile
     * @param string $templateId
     * @param array $data
     * @return IMessageBag
     */
    public function send($mobile, $templateId, $data): IMessageBag
    {
        // TODO: Implement send() method.
    }
}
