<?php

namespace Tests\Feature\Course;

use Tests\Feature\BasicPageTestCase;

class ElectiveTest extends BasicPageTestCase
{

    /**
     * 测试正常获取选修课列表
     */
    public function testItCanGetCourseElective()
    {
        $this->withoutExceptionHandling();
        $data =  ['api_token' => '814ec70b-1d11-4265-9c73-c91d46df6278'];

        $response = $this->post(
            route('api.course.elective.list'),
            $data
        );

        $data = json_decode($response->content(), true);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('data', $data,'返回数据中必须有 data 字段');
        $this->assertIsArray($data['data'], 'data 必须是个数组');

        foreach ($data['data'] as $data) {
            $this->assertArrayHasKey('course_id', $data, '返回数据中必须有 course_id 字段');
            $this->assertArrayHasKey('course_name', $data, '返回数据中必须有 course_name 字段');
            $this->assertArrayHasKey('course_time', $data, '返回数据中必须有 course_time 字段');
            $this->assertArrayHasKey('value', $data, '返回数据中必须有 value 字段');
            $this->assertArrayHasKey('seats', $data, '返回数据中必须有 seats 字段');
            $this->assertArrayHasKey('applied', $data, '返回数据中必须有 applied 字段');
            $this->assertArrayHasKey('expired_at', $data, '返回数据中必须有 expired_at 字段');
            $this->assertIsArray($data['course_time'], 'course_time 必须是个数组');
            foreach ($data['course_time'] as $time ) {
                $this->assertArrayHasKey('weeks', $time, '返回数据中必须有 weeks 字段');
                $this->assertArrayHasKey('time', $time, '返回数据中必须有 time 字段');
                $this->assertArrayHasKey('location', $time, '返回数据中必须有 location 字段');
            }
        }

    }

    /**
     * 测试正常获取选修课详情
     */
    public function testItCanGetCourseElectiveDetails()
    {
        $this->withoutExceptionHandling();
        $data =  ['api_token' => '814ec70b-1d11-4265-9c73-c91d46df6278', 'course_id' => 9];

        $response = $this->post(
            route('api.course.elective.details'),
            $data
        );

        $data = json_decode($response->content(), true);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('data', $data,'返回数据中必须有 data 字段');
        $this->assertArrayHasKey('course_name', $data['data'],'返回数据中必须有 course_name 字段');
        $this->assertArrayHasKey('value', $data['data'],'返回数据中必须有 value 字段');
        $this->assertArrayHasKey('seats', $data['data'],'返回数据中必须有 seats 字段');
        $this->assertArrayHasKey('applied', $data['data'],'返回数据中必须有 applied 字段');
        $this->assertArrayHasKey('schedules', $data['data'],'返回数据中必须有 schedules 字段');
        $this->assertArrayHasKey('expired_at', $data['data'],'返回数据中必须有 expired_at 字段');
        $this->assertArrayHasKey('threshold', $data['data'],'返回数据中必须有 threshold 字段');
        $this->assertArrayHasKey('introduction', $data['data'],'返回数据中必须有 introduction 字段');
        $this->assertIsArray($data['data']['teacher_name'], 'teacher_name 必须是个数组');
        foreach ($data['data']['teacher_name'] as $teacherName) {
            $this->assertArrayHasKey('name', $teacherName, '返回数据中必须有 name 字段');
        }
        $this->assertIsArray($data['data']['schedules'], 'schedules 必须是个数组');
        foreach ($data['data']['schedules'] as $schedule) {
            $this->assertArrayHasKey('weeks', $schedule, '返回数据中必须有 weeks 字段');
            $this->assertArrayHasKey('time', $schedule, '返回数据中必须有 time 字段');
            $this->assertArrayHasKey('location', $schedule, '返回数据中必须有 location 字段');
        }
    }

}
