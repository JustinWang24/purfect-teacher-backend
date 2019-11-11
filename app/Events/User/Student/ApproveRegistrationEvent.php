<?php

namespace App\Events\User\Student;

use App\Events\CanReachByMobilePhone;
use App\Models\RecruitStudent\RegistrationInformatics;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ApproveRegistrationEvent extends AbstractRegistrationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * ApproveRegistrationEvent constructor.
     * @param RegistrationInformatics $registrationForm
     */
    public function __construct(RegistrationInformatics $registrationForm)
    {
        parent::__construct($registrationForm);
    }

    public function getSmsTemplateId(): string
    {
        // TODO: 当报名学生的报名表被 pass 后的短信模板 ID 还未知
        return '';
    }

    public function getSmsContent(): array
    {
        // TODO: 当报名学生的报名表被 pass 后的发送的短信内容
        return [];
    }
}
