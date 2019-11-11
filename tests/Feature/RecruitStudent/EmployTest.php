<?php

namespace Tests\Feature\RecruitStudent;

use Tests\Feature\BasicPageTestCase;

class EmployTest extends BasicPageTestCase
{

    /**
     * 测试正确获取所有未分配班级的人
     */
    public function testItCanUnassignedGradesApi()
    {
        $this->withoutExceptionHandling();
        $su = $this->getSchoolManager();
        $data = ['sort' => 'desc'];
        $response = $this->setSchoolAsUser($su, 1)
                        ->actingAs($su)
                        ->withSession($this->schoolSessionData)
                        ->post(route('api.get.unassigned.grades'), $data);
        dd($response->content());
    }



}
