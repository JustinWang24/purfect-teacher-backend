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
        $response = $this->setSchoolAsUser($su, 50)
                        ->actingAs($su)
                        ->withSession($this->schoolSessionData)
                        ->post(route('api.get.unassigned.grades'));
        dd($response->content());
    }

    /**
     * 测试正确的分配班级
     */
    public function testItCanDistributionGradesApi()
    {
        $this->withoutExceptionHandling();
        $data = [
            [ "user_id" => "168005",
              "name" => 'Woodrow Crist',
              "grade_id" => '1',
            ],
            ["user_id" => "168007",
              "name" => 'Hal Kuhn',
              "grade_id" => '1',
            ],
        ];
        $su = $this->getSchoolManager();
        $response = $this->setSchoolAsUser($su, 50)
                        ->actingAs($su)
                        ->withSession($this->schoolSessionData)
                        ->post(route('api.distribution.grades'), $data);
        dd($response->content());
    }

}
