<?php

namespace App\Events\User;

use App\Dao\Users\UserVerificationDao;
use App\Events\CanReachByMobilePhone;
use App\Models\Users\UserVerification;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendCodeVisiterMobileEvent implements CanReachByMobilePhone
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $user;
    private $mobile;
    private $schoolName;
    private $sendUrl;

    /**
     * @param User $user
     * @param $mobile
     */
    public function __construct($user, $mobile, $schoolName, $sendUrl)
    {
        $this->user = $user;
        $this->mobile = $mobile;
        $this->schoolName = $schoolName;
        $this->sendUrl = $sendUrl;
    }

    /**
     * @return string
     */
    public function getMobileNumber(): string
    {
        return $this->mobile;
    }

    /**
     * @return string
     */
    public function getSmsTemplateId(): string
    {
        return  "488872"; // 短信模板ID
    }

    /***
     * @return array
     */
    public function getSmsContent(): array
    {
        return  [$this->schoolName, $this->sendUrl, '' , '' , '' , '' , '' , '' , '' , 1];
        // 5位随机数 1分钟过期  不知道为啥要 ,'' ,'' ,'' ,'' 这样写
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return  $this->user;
    }


    /**
     * @inheritDoc
     */
    public function getAction(): int
    {
        // TODO: Implement getAction() method.
    }
}
