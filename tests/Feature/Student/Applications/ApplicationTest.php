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
     * 测试创建类型
     */
    public function testCreateApplicationType() {
        $url = 'school_manager.students.applications-set-save';
        $user = $this->getSuperAdmin();
        $this->withoutExceptionHandling();

        $data['type'] = [
            'name'=>$this->randomCreateChinese(4),
            'media_id' =>1,
            ];
        $response = $this->setSchoolAsUser($user, 1)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route($url,$data));
//        dd($response->content());
    }



    /**
     * 测试申请类型列表
     */
    public function testApplicationTypeList() {
        $this->withoutExceptionHandling();

        $header = $this->getHeaderWithApiToken();
        $response = $this->get(route('api.students.applications-type'),
            $header);
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS,
            $result['code']);
        $this->assertArrayHasKey('type', $result['data']);

        if(!empty($result['data']['type'])) {
            foreach ($result['data']['type'] as $key => $val){
                $this->assertArrayHasKey('name', $val);
                $this->assertArrayHasKey('media', $val);
                if(!empty($val['media'])) {
                    $this->assertArrayHasKey('url', $val['media']);
                }
            }
        }
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
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS,
            $result['code']);
        return $result;
    }
}
