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
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.get.preset.step'));

        $data = json_decode($response->content(), true);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('data', $data,'返回数据中必须有 data 字段');
        $this->assertIsArray($data['data'], 'data 必须是个数组');
        foreach ($data['data'] as $val) {
            $this->assertArrayHasKey('id', $val, '返回的数据中必须有 id 字段');
            $this->assertArrayHasKey('name', $val, '返回的数据中必须有 name 字段');
            $this->assertArrayHasKey('describe', $val, '返回的数据中必须有 describe 字段');
            $this->assertArrayHasKey('level', $val, '返回的数据中必须有 level 字段');
        }
    }

    /**
     * 测试正常添加公文流程
     */
    public function testItCanAddProductionProcess()
    {
        $this->withoutExceptionHandling();
        $data = ['name' => '流程名称', 'process_data' => ['1' => '1', '3' => '2', '6' => '3', '9' => '4', '10' => '5'] ];
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('teacher.production.process', $data));

        $data = json_decode($response->content(), true);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('data', $data,'返回数据中必须有 data 字段');
        $this->assertStringContainsString($data['code'], 1000);
    }

    /**
     * 测试是否正常获取该学校所有公文流程
     */
    public function testItCanGetOfficialDocument()
    {
        $this->withoutExceptionHandling();
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.get.official.document'));

        $data = json_decode($response->content(), true);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('data', $data,'返回数据中必须有 data 字段');
        $this->assertIsArray($data['data'], 'data 必须是个数组');
        foreach ($data['data'] as $val) {
            $this->assertArrayHasKey('id', $val, '返回的数据中必须有 id 字段');
            $this->assertArrayHasKey('name', $val, '返回的数据中必须有 name 字段');
        }
    }

    /**
     * 测试是否正常获取一条公文流程的详情
     */
    public function testItCanGetProcessDetails()
    {
        $this->withoutExceptionHandling();
        $data = ['progress_id' => '1'];
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.get.one.process', $data));
        $data = json_decode($response->content(), true);

        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('data', $data,'返回数据中必须有 data 字段');
        $this->assertIsArray($data['data'], 'data 必须是个数组');
        foreach ($data['data'] as $val) {
           $this->assertArrayHasKey('id', $val, '返回数据中必须有 id 字段');
           $this->assertArrayHasKey('preset_step_id', $val, '返回数据中必须有 preset_step_id 字段 ');
           $this->assertArrayHasKey('index', $val, '数据中必须有 index 字段');
           $this->assertArrayHasKey('preset_step', $val, '返回数据中必须有 preset_step 字段');
           $this->assertIsArray($val['progress_steps_user'], 'progress_steps_user 必须是个数组');
        }

    }

    /**
     * 测试正常添加步骤负责人
     */
    public function testAddStepUser()
    {
        $this->withoutExceptionHandling();
        $data = ['step_user' => ['progress_steps_id' => '1', 'user_id' => '30', 'type' => '1']];
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('teacher.add.step.user', $data));

        $data = json_decode($response->content(), true);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('data', $data,'返回数据中必须有 data 字段');
        $this->assertStringContainsString($data['code'], 1000);
    }

    /**
     * 测试正常修改步骤负责人
     */
    public function testUpdateStepUser()
    {
        $this->withoutExceptionHandling();
        $data = ['step_user' => ['user_id' => '31', 'type' => '2'], 'id' => '2'];
        $su = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('teacher.update.step.user', $data));

        $data = json_decode($response->content(), true);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('data', $data,'返回数据中必须有 data 字段');
        $this->assertStringContainsString($data['code'], 1000);
    }



}
