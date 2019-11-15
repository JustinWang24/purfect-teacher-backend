<?php

namespace App\Events\User\Student;

use App\Dao\Schools\MajorDao;
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
use App\Dao\Schools\SchoolDao;

/**
 * 当学生的报名表获得批准发送短信
 * Class ApproveRegistrationEvent
 * @package App\Events\User\Student
 */
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
        return  '483488';
    }

    public function getSmsContent(): array
    {
        return [$this->getUserName(), $this->getSchoolName(), $this->getMajorName(), '通过', $this->getAdmissionOfficeMobile()];
    }

    public function getSchoolName()
    {
        $dao = new SchoolDao;
        $result = $dao->getSchoolById($this->form->school_id);
        if ($result) {
            return $result->name;
        }
    }

    public function getMajorName()
    {
        $dao = new MajorDao;
        $result = $dao->getMajorById($this->form->major_id);
        if ($result) {
            return $result->name;
        }
    }

    public function getAdmissionOfficeMobile()
    {
        // todo: 实现获取招生办电话的方法
        return 100000;
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
        return '学生'.$this->getUser()['name'].'报名信息已通过,请及时查看';
    }

    public function getNextMove(): string
    {
        // TODO: 实现学生报名表被批准进入录取流程的事件发生时, 站内消息的 下一步 的真实内容
        return '';
    }
}
