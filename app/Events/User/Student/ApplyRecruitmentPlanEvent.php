<?php

namespace App\Events\User\Student;

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
}
