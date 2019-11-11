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

class EnrolRegistrationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $form;

    /**
     * EnrolRegistrationEvent constructor.
     * @param RegistrationInformatics $form
     */
    public function __construct(RegistrationInformatics $form)
    {
        $this->form = $form;
    }
}
