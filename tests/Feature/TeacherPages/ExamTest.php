<?php
namespace Tests\Feature\TeacherPages;
use Carbon\Carbon;
use Tests\Feature\BasicPageTestCase;

class ExamTest extends BasicPageTestCase
{
    public function testAddExamPage()
    {
        $user = $this->getTeacher();

        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.exam.index',['uuid'=>'6fec3fe9-da7a-44a2-9ce1-1a541e931bec']));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="exam-name-input"');              //考试名称
        $response->assertSee('id="exam-course_id-input"');         //课程ID
        $response->assertSee('id="exam-semester-input"');          //学期
        $response->assertSee('id="exam-formalism-radio"');         //考试形式
        $response->assertSee('id="exam-type-radio"');              //考试类型
        $response->assertSee('id="exam-room_id-select"');          //考试教室
        $response->assertSee('id="exam-from-date"');               //考试开始时间
        $response->assertSee('id="exam-to-date"');                 //考试结束时间

    }


    /**
     * 创造数据
     * @return array
     */
    public function __createData()
    {
        $data = [
            'name'=>Carbon::now()->format('Y').'年秋期末考试',
            'course_id'=>'1',
            'semester' => 1,
            'formalism'=> 1,
            'type'     => 1,
            'room_id'  => 1,
            'from'     => Carbon::now()->format('Y-m-d 10:00:00'),
            'to'       => Carbon::now()->format('Y-m-d 12:00:00'),
        ];
        return $data;
    }


    public function testCreateApi()
    {
        $user = $this->getTeacher();

        $data = $this->__createData();

        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.exam.create',$data));

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
    }

}