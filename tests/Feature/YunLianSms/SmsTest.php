<?php


namespace Tests\Feature\YunLianSms;

use Tests\Feature\BasicPageTestCase;

class SmsTest extends BasicPageTestCase
{

    /**
     * 测试正确云联接口
     */
    public function testYunLianSms()
    {
        $this->withoutExceptionHandling();
        $su = $this->getSchoolManager();
        $response = $this->setSchoolAsUser($su, 50)
                        ->actingAs($su)
                        ->withSession($this->schoolSessionData)
                        ->get(route('api.test-sms'));
        dd($response->content());

    }


}
