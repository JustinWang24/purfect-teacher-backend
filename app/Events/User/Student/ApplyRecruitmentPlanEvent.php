<?php

namespace App\Events\User\Student;

use App\Dao\Users\UserDao;
use App\Models\Misc\SystemNotification;
use App\Models\RecruitStudent\RegistrationInformatics;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ApplyRecruitmentPlanEvent extends AbstractRegistrationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * ApplyRecruitmentPlanEvent constructor.
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
    public function getSystemContent(): string
    {
        return '学生'.$this->getUser()['name'].'已提交报名信息,请及时查看';
    }

    public function getNextMove(): string
    {
        // TODO: 获取下一步操作
        return '';
    }

    public function getAction(): int
    {
        // TODO: Implement getAction() method.
    }
}
