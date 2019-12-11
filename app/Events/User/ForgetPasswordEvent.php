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
        return  "123";
        // todo :: 忘记密码模板ID
    }

    public function getSmsContent(): array
    {
        return  ['忘记密码'];
        // todo :: 忘记密码短信内容
    }

    public function getUser(): User
    {

    }


}
