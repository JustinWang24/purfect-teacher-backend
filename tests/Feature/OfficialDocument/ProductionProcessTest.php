<?php


namespace Tests\Feature\OfficialDocument;

use Tests\Feature\BasicPageTestCase;

class ProductionProcessTest extends  BasicPageTestCase
{


    /**
     * 测试正常添加公文流程
     */
    public function testItCanAddProductionProcess()
    {
        $this->withoutExceptionHandling();
        $data = ['name' => '流程名称', 'preset_step_id' => ['1' => '1', '3' => '2', '6' => '3', '9' => '4', '10' => '5'] ];
        $su = $this->getTeacher();
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('teacher.production.process', $data));

        dd($response->content());
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
        dd($response->content());
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
        dd($response->content());
    }


}
