<?php

namespace Tests\Feature\Student\Applications;

use App\Utils\JsonBuilder;
use Tests\Feature\BasicPageTestCase;

class ApplicationTest extends BasicPageTestCase
{


    public function _createData() {
        $data = [
            'application_type_id' => 1,
            'reason'              => $this->randomCreateChinese(50),
            'census'              => 1,
            'family_population'   => 4,
            'general_income'      => 10000,
            'per_capita_income'   => 2000,
            'income_source'       => '打工',
            'media_id'            => [1],
        ];

        return ['application'=>$data];
    }

    /**
     * 测试创建申请
     */
    public function testCreateApplication() {
        $this->withoutExceptionHandling();

        $header = $this->getHeaderWithApiToken();
        $data = $this->_createData();

        $response = $this->post(route('api.students.applications-create'),
            $data,$header);
        $result = json_decode($response->content(),true);
        dd($result);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS,
            $result['code']);
        return $result;
    }
}
