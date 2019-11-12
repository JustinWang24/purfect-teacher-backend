<?php

namespace App\Events\User\Student;

use App\Dao\Schools\MajorDao;
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
     * @param RegistrationInformatics $registrationForm
     */
    public function __construct(RegistrationInformatics $registrationForm)
    {
        parent::__construct($registrationForm);
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
        return 100000;
    }



}
