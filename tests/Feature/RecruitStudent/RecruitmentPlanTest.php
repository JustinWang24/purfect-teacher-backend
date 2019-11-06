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
            ->post(route('api.load.open.majors'), $data);
        dd($response->content());
    }

    /**
     * 测试正确获取专业详情
     */
    public function testItCanGetMajorDetailApi()
    {
        $this->withoutExceptionHandling();
        $su = $this->getSchoolManager();
        $data = ['id' => '2'];
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('api.load.major.detail'), $data);
        dd($response->content());
    }


    /**
     * 测试正确获取已报名学生, 辅助填充数据
     */
    public function testItCanGetQueryStudentProfileApi()
    {
        $this->withoutExceptionHandling();
        $su = $this->getSchoolManager();
        $data = ['id_number' => '201928128038509'];
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('api.query.student.profile'), $data);
        dd($response->content());
    }

    /**
     * 测试正确提添加学生报名
     */
    public function testItCanSubmitFormApi()
    {
        $this->withoutExceptionHandling();
        $su = $this->getSchoolManager();
        $data = ['id' => '', 'major_id' => '1', 'school_id' => '1' , 'recruitment_plan_id' => '1', 'data' => ['name' => '123', 'mobile' => 12211201202,
                                                           'gender' => 1, 'parent_name' => '帕菲特',
                                                           'parent_mobile' => '110', 'relocation_allowed' => '1']
            ];

        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('api.major.submit.form'), $data);
        dd($response->content());
    }

}
