<?php

namespace App\Utils\Misc\Impl;

use App\Utils\Misc\Contracts\JPushSender;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use App\Utils\JsonBuilder;
use JPush\Client as JPush;
use Illuminate\Support\Facades\Log;

class JPushLogic implements JPushSender
{
    private $appKey;

    private $masterSecret;

    use PushUserRole;


    public function send($users, $title, $content, $extras): IMessageBag
    {
        if (is_array($users)) {
            $result = $this->setKeyAndRegId($users);
        } else {
            return false;
        }

        foreach ($result as $key => $val) {
            if (!empty($val)) {

                $iosNotification     = ['sound' => '', 'extras' => $extras];
                $androidNotification = ['title' => $title, 'extras' => $extras];
                $options             = ['apns_production' => 'False'];
                $client              = new JPush($val['key']['appKey'], $val['key']['masterSecret'], null, null, 'BJ');
                try {
                    $push     = $client->push();
                    $response = $push
                        ->setPlatform('all') // 推送平台
                        ->addRegistrationId($val['regId']) // 极光ID
                        ->setNotificationAlert($title) // 消息标题
                        ->iosNotification($content, $iosNotification)
                        ->androidNotification($content, $androidNotification)
                        ->options($options)
                        ->send();
                } catch (\JPush\Exceptions\APIRequestException $e) {
                    $error[] = ['message' => $e->getMessage(), 'code' => $e->getCode()];
                    Log::alert('push推送失败:  ' . 'message:' . $e->getMessage() . '   ,code:' . $e->getCode());
                }
            }
        }
        if (!empty($error)) {
            return new MessageBag(JsonBuilder::CODE_ERROR,'推送失败', $error);
        }
        return new MessageBag(JsonBuilder::CODE_SUCCESS, '推送成功');
    }


}
