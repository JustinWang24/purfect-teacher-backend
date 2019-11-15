<?php
/**
 * 学生录取后发生的事件
 */
namespace App\Events\User\Student;

use App\Models\RecruitStudent\RegistrationInformatics;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Dao\Schools\SchoolDao;
use App\Dao\Schools\MajorDao;

class EnrolRegistrationEvent extends AbstractRegistrationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * EnrolRegistrationEvent constructor.
     * @param RegistrationInformatics $form
     */
    public function __construct(RegistrationInformatics $form)
    {
        parent::__construct($form);
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

    public function getSystemContent(): string
    {
        // TODO: Implement getContent() method.
    }

    public function getNextMove(): string
    {
        // TODO: Implement getNextMove() method.
    }

    public function getSmsTemplateId(): string
    {
        return '483489';
    }

    public function getSmsContent(): array
    {
        return [$this->getUserName(), $this->getSchoolName(), $this->getMajorName()];
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

}
