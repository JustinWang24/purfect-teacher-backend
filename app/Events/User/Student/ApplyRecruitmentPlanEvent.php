<?php

namespace App\Events\User\Student;

use App\Http\Controllers\Operator\RecruitStudent\RegistrationInformatics;
use App\Models\Contract\ContentHolder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ApplyRecruitmentPlanEvent implements ContentHolder
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

    public function getContentByMobile($mobile): array
    {
        // TODO: Implement getContentByMobile() method.
    }
}
