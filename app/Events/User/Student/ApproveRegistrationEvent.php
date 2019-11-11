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

class ApproveRegistrationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $form;

    /**
     * ApproveRegistrationEvent constructor.
     * @param RegistrationInformatics $registrationForm
     */
    public function __construct(RegistrationInformatics $registrationForm)
    {
        $this->form = $registrationForm;
    }
}
