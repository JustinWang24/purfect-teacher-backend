<?php

namespace App\Events\User\Student;

use App\Models\RecruitStudent\RegistrationInformatics;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ApplyRecruitmentPlanEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var RegistrationInformatics
     */
    public $form;

    /**
     * ApplyRecruitmentPlanEvent constructor.
     * @param RegistrationInformatics $form
     */
    public function __construct(RegistrationInformatics $form)
    {
        $this->form = $form;
    }
}
