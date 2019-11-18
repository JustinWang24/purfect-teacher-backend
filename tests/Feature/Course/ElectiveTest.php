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
        $su = $this->getStudent();
        $data =  ['api_token' => '814ec70b-1d11-4265-9c73-c91d46df6278'];

        $response = $this->post(
            route('api.course.elective.list'),
            $data
        );

        dd($response->content());
    }

    /**
     * 测试正常获取选修课详情
     */
    public function testItCanGetCourseElectiveDetails()
    {
        $this->withoutExceptionHandling();
        $su = $this->getStudent();
        $data =  ['api_token' => '814ec70b-1d11-4265-9c73-c91d46df6278', 'course_id' => 1];

        $response = $this->post(
            route('api.course.elective.details'),
            $data
        );

        dd($response->content());

    }

}
