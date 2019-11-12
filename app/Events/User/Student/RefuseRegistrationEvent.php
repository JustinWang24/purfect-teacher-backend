<?php

namespace App\Events\User\Student;

use App\Models\RecruitStudent\RegistrationInformatics;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RefuseRegistrationEvent extends AbstractRegistrationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * RefuseRegistrationEvent constructor.
     * @param RegistrationInformatics $form
     */
    public function __construct(RegistrationInformatics $form)
    {
        parent::__construct($form);
    }

    public function getSmsTemplateId(): string
    {
        // TODO: 当报名学生的报名表被 拒绝 refuse 后的短信模板 ID 还未知
        return '';
    }

    public function getSmsContent(): array
    {
        // TODO: 当报名学生的报名表被 拒绝 refuse 后的发送的短信内容
        return [];
    }

    public function getForm(): RegistrationInformatics
    {
        // TODO: Implement getForm() method.
    }

    public function getMessageType(): int
    {
        // TODO: Implement getMessageType() method.
    }

    public function getPriority(): int
    {
        // TODO: Implement getPriority() method.
    }

    public function getContent(): string
    {
        // TODO: Implement getContent() method.
    }

    public function getNextMove(): string
    {
        // TODO: Implement getNextMove() method.
    }


}
