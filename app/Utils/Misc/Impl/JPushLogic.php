<?php

namespace App\Utils\Misc\Impl;

use App\Utils\Misc\Contracts\JPushSender;
use App\Utils\ReturnData\IMessageBag;
use App\Models\Users\Role;
use JPush\Client as JPush;

class JPushLogic implements JPushSender
{
    private $appKey;

    private $masterSecret;


    public function setKey($type)
    {
        if ($type == Role::VERIFIED_USER_STUDENT || $type == Role::VERIFIED_USER_CLASS_LEADER || $type == Role::VERIFIED_USER_CLASS_SECRETARY) {
            // 学生端
            $this->appKey       = env('PUSH_STUDENT_KEY');
            $this->masterSecret = env('PUSH_STUDENT_SECRET');

        } elseif ($type == Role::TEACHER || Role::EMPLOYEE) {

            // 教师端
            $this->appKey       = env('PUSH_TEACHER_KEY');
            $this->masterSecret = env('PUSH_TEACHER_SECRET');

        } elseif ($type == Role::COMPANY || $type == Role::DELIVERY || $type == Role::BUSINESS_INNER || $type == BUSINESS_OUTER) {

            // 商企端
            $this->appKey       = env('15a8a325231ac1c39005a1ba');
            $this->masterSecret = env('ffe404a15740e7a63a9f49c7');
        } else {
            $this->appKey       = null;
            $this->masterSecret = null;
        }

    }


    public function user($user)
    {
        $this->setKey($user->type);
    }

    public function send($user, $title, $content, $extras): IMessageBag
    {
        $client  = new JPush($this->appKey, $this->masterSecret, null, null, 'BJ');
        $payload = $client->push()
                    ->setPlatform('all')
                    ->addAllAudience()
                    ->setNotificationAlert('Hi, JPush');

    }


}
