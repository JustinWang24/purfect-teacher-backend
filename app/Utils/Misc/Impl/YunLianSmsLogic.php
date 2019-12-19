<?php

namespace App\Utils\Misc\Impl;

use App\Utils\Misc\Contracts\ISmsSender;
use App\ThirdParty\YunLianSDK\Rest;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use App\Utils\JsonBuilder;
use Illuminate\Support\Facades\Log;

class YunLianSmsLogic implements ISmsSender
{
    // 主帐号
    private $accountSid;

    // 主帐号Token
    private $accountToken;

    // 应用Id
    private $appId;

    // 请求地址
    private $serverIP;

    // 请求端口
    private $serverPort;

    // REST版本号
    private $softVersion;


    public function __construct()
    {
        $this->accountSid   = env('YUN_LIAN_ACCOUNTS_SID');
        $this->accountToken = env('YUN_LIAN_ACCOUNTS_TOKEN');
        $this->appId        = env('YUN_LIAN_APP_ID');
        $this->serverIP     = 'app.cloopen.com';
        $this->serverPort   = '8883';
        $this->softVersion  = '2013-12-26';
    }

    /**
     * @param string $mobile
     * @param string $templateId
     * @param array $data
     * @return MessageBag
     */
    public function send($mobile, $templateId, $data): IMessageBag
    {
        if (env('YUN_LIAN_APP_ID') != 'TEST') {

             $rest = new Rest($this->serverIP, $this->serverPort, $this->softVersion);
             $rest->setAccount($this->accountSid, $this->accountToken);
             $rest->setAppId($this->appId);
             // 发送模板短信
             $result = $rest->sendTemplateSMS($mobile, $data, $templateId);
             if($result == NULL ) {
                 // 未知错误
                 return  new MessageBag(JsonBuilder::CODE_ERROR, '云联云通讯接口错误');
             }
             if($result->statusCode!=0) {
                 // 错误信息
                 return  new MessageBag($result->statusCode, $result->statusMsg);
             }else{
                 // 获取返回信息 成功处理逻辑
                 return  new MessageBag(JsonBuilder::CODE_SUCCESS, '发送成功');
             }
        } else {
            Log::channel('smslog')->alert('短信接口请求成功了:'. 'mobile:'. $mobile . ',templateId:'. $templateId. ',data:'. var_export($data,true));
            return new  MessageBag(JsonBuilder::CODE_SUCCESS, '短信接口请求成功');
        }

    }
}
