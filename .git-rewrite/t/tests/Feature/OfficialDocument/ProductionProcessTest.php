<?php

namespace Tests\Feature\OfficialDocument;

use Tests\Feature\BasicPageTestCase;

class ProductionProcessTest extends  BasicPageTestCase
{

    /**
     * 测试正常获取系统预置步骤
     */
    public function testItCanGetPresetStep()
    {
        $this->withoutExceptionHandling();
        $su = $this->getTeacher();
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.get.preset.step'));
        $this->assertTrue(1===2);
    }

    /**
     * 测试正常添加公文流程
     */
    public function testItCanAddProductionProcess()
    {
        $this->withoutExceptionHandling();
        $data = ['name' => '流程名称', 'process_data' => ['1' => '1', '3' => '2', '6' => '3', '9' => '4', '10' => '5'] ];
        $su = $this->getTeacher();
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('teacher.production.process', $data));

        $this->assertTrue(1===2);
    }

    /**
     * 测试是否正常获取该学校所有公文流程
     */
    public function testItCanGetOfficialDocument()
    {
        $this->withoutExceptionHandling();
        $su = $this->getTeacher();
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.get.official.document'));
        $this->assertTrue(1===2);
    }

    /**
     * 测试是否正常获取一条公文流程的详情
     */
    public function testItCanGetProcessDetails()
    {
        $this->withoutExceptionHandling();
        $data = ['progress_id' => '1'];
        $su = $this->getTeacher();
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.get.one.process', $data));
        $this->assertTrue(1===2);
    }

    /**
     * 测试正常添加步骤负责人
     */
    public function testAddStepUser()
    {
        $this->withoutExceptionHandling();
        $data = ['step_user' => ['progress_steps_id' => '1', 'user_id' => '30', 'type' => '1']];
        $su = $this->getTeacher();
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('teacher.add.step.user', $data));
        $this->assertTrue(1===2);
    }

    /**
     * 测试正常修改步骤负责人
     */
    public function testUpdateStepUser()
    {
        $this->withoutExceptionHandling();
        $data = ['step_user' => ['user_id' => '31', 'type' => '2'], 'id' => '2'];
        $su = $this->getTeacher();
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('teacher.update.step.user', $data));
        $this->assertTrue(1===2);
    }



}
