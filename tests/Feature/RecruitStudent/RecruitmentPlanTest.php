<?php

namespace Tests\Feature\RecruitStudent;

use Tests\Feature\BasicPageTestCase;

class RecruitmentPlanTest extends BasicPageTestCase
{

    /**
     * 测试正确获取可以报名的专业
     */
    public function testItCanGetOpenMajorApi()
    {
        $this->withoutExceptionHandling();
        $su = $this->getSchoolManager();
        $data = ['school_id' => '1', 'id_number' => '201928128038509'];
        $response = $this->setSchoolAsUser($su, 6)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('api.load.open.majors', $data));
        dd($response->content());
    }

    /**
     * 测试正确获取专业详情
     */
    public function testItCanGetMajorDetailApi()
    {
        $this->withoutExceptionHandling();
        $su = $this->getSchoolManager();
        $data = ['id' => '1'];
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('api.load.major.detail', $data));
        dd($response->content());
    }


    /**
     * 测试正确获取专业详情
     */
    public function testItCanGetQueryStudentProfileApi()
    {
        $this->withoutExceptionHandling();
        $su = $this->getSchoolManager();
        $data = ['id_number' => '201928128038509'];
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('api.query.student.profile', $data));
        dd($response->content());
    }

    /**
     * 测试正确提添加学生报名
     */
    public function testItCanSubmitFormApi()
    {
        $this->withoutExceptionHandling();
        $su = $this->getSchoolManager();
        $data = ['id_number' => '201928128038509'];
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('api.major.submit.form', $data));
        dd($response->content());
    }

}
