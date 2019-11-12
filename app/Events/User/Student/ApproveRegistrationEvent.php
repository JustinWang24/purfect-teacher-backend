<?php

namespace App\Events\User\Student;

use App\Events\CanReachByMobilePhone;
use App\Models\Misc\SystemNotification;
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
     * @param RegistrationInformatics $form
     */
    public function __construct(RegistrationInformatics $form)
    {
        parent::__construct($form);
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

    /**
     * 当报名学生的报名表被 pass 后 获取提交的信息
     * @return RegistrationInformatics
     */
    public function getForm(): RegistrationInformatics
    {
        return $this->form;
    }

    /**
     * 获取信息类型
     * @return int
     */
    public function getMessageType(): int
    {
        return SystemNotification::PRIORITY_MEDIUM;
    }

    /**
     * 获取消息级别
     * @return int
     */
    public function getPriority(): int
    {
        return SystemNotification::TYPE_STUDENT_REGISTRATION;
    }

    /**
     * 获取推送的内容
     * @return string
     */
    public function getContent(): string
    {
        return '学生'.$this->getUser()['name'].'报名信息已通过,请及时查看';
    }

    public function getNextMove(): string
    {
        // TODO: Implement getNextMove() method.
        return '';
    }


}
