<?php

namespace Tests\Feature\EnrolmentStep;

use App\Utils\JsonBuilder;
use Tests\Feature\BasicPageTestCase;

class EnrolmentStepTest extends BasicPageTestCase
{

    /**
     * 测试创建
     * @return mixed
     */
    public function testEnrolmentCreate() {

        $this->withoutExceptionHandling();

        $user = $this->getSuperAdmin();

        $data = ['name'=>'验证信息'.rand(1,99)];
        $response = $this->setSchoolAsUser($user, 1)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->post(route('operator.enrolmentStep.create'),$data);
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS,$result['code']);
        return $result;
    }
}
