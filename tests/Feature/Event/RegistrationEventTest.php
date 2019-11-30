<?php

namespace Tests\Event;

use Tests\Feature\BasicPageTestCase;
use App\Models\RecruitStudent\RegistrationInformatics;
use App\Events\User\Student\ApproveRegistrationEvent;

class RegistrationEventTest extends BasicPageTestCase
{
    private  $testFormId = 4;


    /**
     * 测试当学生的报名表获得批准发送短信事件
     */
    public function testRefuseRegistrationEvent()
    {
        $this->withoutExceptionHandling();

        $form = RegistrationInformatics::find($this->testFormId);
        event(new ApproveRegistrationEvent($form));
    }



}
