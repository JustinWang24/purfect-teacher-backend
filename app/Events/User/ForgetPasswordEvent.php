<?php

namespace App\Events\User;

use App\Events\CanReachByMobilePhone;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ForgetPasswordEvent implements CanReachByMobilePhone
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private  $user;

    /**
     * Create a new event instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getMobileNumber(): string
    {
        return  $this->user->getMobile();
    }

    public function getSmsTemplateId(): string
    {
        return  "189903"; // 短信模板ID
    }

    public function getSmsContent(): array
    {
        return  [rand(10000, 99999), '' , '' , '' , '' , '' , '' , '' , '' , 1];
        // 5位随机数 1分钟过期  不知道为啥要 ,'' ,'' ,'' ,'' 这样写
    }

    public function getUser(): User
    {

    }


}
