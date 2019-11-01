<?php


namespace Tests\Feature\RecruitStudent;

use Tests\Feature\BasicPageTestCase;

class RegistrationInformationTest extends BasicPageTestCase
{

    /**
     * 测试正常获取报名信息列表
     */
    public function testItCanGetRegistrationList()
    {
        $this->withoutExceptionHandling();
        $su = $this->getSchoolManager();
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.registration.list'));
        dd($response->content());
    }


}
