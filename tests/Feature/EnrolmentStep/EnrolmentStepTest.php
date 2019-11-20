<?php

namespace Tests\Feature\EnrolmentStep;

use App\Utils\JsonBuilder;
use Tests\Feature\BasicPageTestCase;
use App\Models\EnrolmentStep\SchoolEnrolmentStepTask;

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
        $this->assertEquals(JsonBuilder::CODE_SUCCESS,
            $result['code']);
        return $result;
    }


    /**
     * 测试获取系统迎新列表
     */
    public function testGetEnrolmentStepList() {

        $header = $this->getHeaderWithApiTokenForTeacher();
        $response = $this->get(
            route('api.enrolmentStep.getEnrolmentStepList'),
            $header);

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS,
            $result['code']);

        if(!empty($result['data'])) {
            foreach ($result['data'] as $key => $val) {
                $this->assertArrayHasKey('name', $val);
            }
        }
        return $result['data'];
    }


    public function _createData() {
        $data = [
             'id' => 1,
//                'name' => $return[0]['name'],
            'name' => $this->randomCreateChinese(5),
//                'enrolment_step_id'=> $return[0]['id'],
            'enrolment_step_id'=> 1,
            'school_id' => 1,
            'campus_id' => 1 ,// 当前老师所在的校区
            'describe'  => $this->randomCreateChinese(100),
            'sort'      => rand(1,9),
            'user_id' => 10, // 获取老师接口
            'assists' => [11,17,18], // 协助人ID集合
            'medias'  => [1], // 媒体ID
            'tasks' => [
                [
                    'name' => $this->randomCreateChinese(5),
                    'describe' => $this->randomCreateChinese(50),
                    'type' => SchoolEnrolmentStepTask::TYPE_REQUIRED,
                ]
            ],
        ];
        return [
            'enrolment'=>$data,
            ];
    }


    /**
     * 测试创建/修改 学校迎新流程
     */
    public function testSaveSchoolEnrolmentStep() {
        $this->withoutExceptionHandling();

        $data = $this->_createData();
        $header = $this->getHeaderWithApiTokenForTeacher();

        $response = $this->post(
            route('api.schoolEnrolmentStep.saveEnrolment'),
            $data,$header);

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS,
            $result['code']);
        return $result['data'];
    }


    /**
     * 测试获取学校迎新步骤详情
     */
    public function testGetSchoolEnrolmentStepInfo() {

        $this->withoutExceptionHandling();

        $data = ['id'=>1];
        $header = $this->getHeaderWithApiTokenForTeacher();

        $response = $this->post(
            route('api.schoolEnrolmentStep.getEnrolmentInfo'),
            $data,$header);
        dd($response->content());
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS,
            $result['code']);
        $this->assertArrayHasKey('id', $result['data']);
        $this->assertArrayHasKey('name', $result['data']);
        $this->assertArrayHasKey('enrolment_step_id', $result['data']);
        $this->assertArrayHasKey('user', $result['data']);
        $this->assertArrayHasKey('name', $result['data']['user']);
        $this->assertArrayHasKey('mobile', $result['data']['user']);

        if(!empty($result['data']['assists'])) {
            foreach ($result['data']['assists'] as $key => $val) {
                $this->assertArrayHasKey('id', $val);
                $this->assertArrayHasKey('user', $val);
                $this->assertArrayHasKey('name', $val['user']);
                $this->assertArrayHasKey('mobile', $val['user']);
            }
        }

        if(!empty($result['data']['medias'])) {
            foreach ($result['data']['medias'] as $key => $val) {
                $this->assertArrayHasKey('id', $val);
                $this->assertArrayHasKey('media', $val);
                $this->assertArrayHasKey('file_name', $val['media']);
                $this->assertArrayHasKey('url', $val['media']);
            }
        }

        if(!empty($result['data']['tasks'])) {
            foreach ($result['data']['tasks'] as $key => $val) {
                 $this->assertArrayHasKey('id', $val);
                 $this->assertArrayHasKey('name', $val);
                 $this->assertArrayHasKey('type', $val);
            }
        }

    }





    /**
     * 测试删除
     * @depends testSaveSchoolEnrolmentStep
     */
    public function testDeleteSchoolEnrolmentStep($return) {
        $header = $this->getHeaderWithApiTokenForTeacher();

        $data = ['id'=>$return['id']];
        $response = $this->post(
            route('api.schoolEnrolmentStep.deleteEnrolment'),
            $data,$header);
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(JsonBuilder::CODE_SUCCESS,
            $result['code']);
    }





}
