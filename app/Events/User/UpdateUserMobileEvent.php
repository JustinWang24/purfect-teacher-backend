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

class UpdateUserMobileEvent implements CanReachByMobilePhone
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private  $mobile;

    private  $user;

    /**
     * @param User $user
     * @param $mobile
     */
    public function __construct($user ,$mobile)
    {
        $this->user = $user;
        $this->mobile = $mobile;
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
        return  "189903"; // 短信模板ID
    }

    /***
     * @return array
     */
    public function getSmsContent(): array
    {
        return  [rand(10000, 99999), '' , '' , '' , '' , '' , '' , '' , '' , 1];
        // 5位随机数 1分钟过期  不知道为啥要 ,'' ,'' ,'' ,'' 这样写
    }


     /**
     * @return int
     */
    public function getAction(): int
    {
        return  UserVerification::PURPOSE_3;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return  $this->user;
    }


}
