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
        $data = ['sort' => 'desc'];
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.registration.list', $data));

        $this->assertTrue(1===2);
    }

    /**
     * 测试正常获取一条报名的详情
     */
    public function testItCanGetOneDataInfo()
    {
        $this->withoutExceptionHandling();
        $su = $this->getSchoolManager();
        $data = ['id' => '1'];
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.registration.details', $data));
        $this->assertTrue(1===2);
    }


    /**
     * 测试正常获取一条报名的详情
     */
    public function testItCanUpdateData()
    {
        $this->withoutExceptionHandling();
        $su = $this->getSchoolManager();
        $data = ['id' => '1', 'data' => ['note' => '124']];
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.registration.examine', $data));
        $this->assertTrue(1===2);
    }
}
