<?php

namespace Tests\Event;

use App\Events\User\ForgetPasswordEvent;
use Tests\Feature\BasicPageTestCase;
use App\Models\RecruitStudent\RegistrationInformatics;
use App\Events\User\Student\ApproveRegistrationEvent;
use App\User;

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

    /**
     *  测试忘记密码事件
     */
    public function testForgetPasswordEvent()
    {
        $this->withoutExceptionHandling();
        $form = User::find($this->testFormId);
        event(new ForgetPasswordEvent($form));
    }


}
