<?php
namespace Tests\Feature\TeacherPages;

use Carbon\Carbon;
use App\Models\Teachers\Exam;
use Tests\Feature\BasicPageTestCase;

class ExamTest extends BasicPageTestCase
{

    /**
     * 创建考试页面
     */
    public function testAddExamPage()
    {
        $user = $this->getTeacher();

        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.exam.index',['uuid'=>'6fec3fe9-da7a-44a2-9ce1-1a541e931bec']));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="exam-name-input"');              // 考试名称
        $response->assertSee('id="exam-course_id-input"');         // 课程ID
        $response->assertSee('id="exam-semester-input"');          // 学期
        $response->assertSee('id="exam-formalism-radio"');         // 考试形式
        $response->assertSee('id="exam-type-radio"');              // 考试类型
        $response->assertSee('id="exam-room_id-select"');          // 考试教室
        $response->assertSee('id="exam-from-date"');               // 考试开始时间
        $response->assertSee('id="exam-to-date"');                 // 考试结束时间

    }


    /**
     * 创造数据
     * @return array
     */
    public function __createData()
    {
        $data = [
            'name'=>Carbon::now()->format('Y').'年秋期末考试',
            'course_id'=>'1',     // 课程ID
            'semester' => 1,      // 学期
            'formalism'=> Exam::FORMALISM_WRITTEN,   // 考试形式
            'type'     => Exam::TYPE_MIDTERM,        // 类型
            'room_id'  => [1,2,3],      // 房间ID
            'from'     => Carbon::tomorrow()->format('Y-m-d 14:00:00'),
            'to'       => Carbon::tomorrow()->format('Y-m-d 16:00:00'),
        ];
        return $data;
    }


    /**
     * 添加考试
     */
    public function testCreateExamApi()
    {
        $data = $this->__createData();

        $user = $this->getTeacher();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.exam.create',$data));

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
    }


    /**
     * 获取考试列表
     */
    public function testGetExamListApi()
    {
        $user = $this->getTeacher();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.exam.data'));

        $result = json_decode($response->content(),true);

        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);

        foreach ($result['data'] as $key => $value) {

             $this->assertArrayHasKey('name', $value);
             $this->assertArrayHasKey('type_text', $value);
             $this->assertArrayHasKey('formalism_text', $value);
                    $this->assertArrayHasKey('rooms', $value);


        }

    }


    /**
     * 获取教室列表
     */
    public function testGetClassRoomApi()
    {
        $user = $this->getTeacher();

        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.exam.getClassRooms'));

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
        foreach ($result['data'] as $key => $val) {
            $this->assertArrayHasKey('id', $val);
            $this->assertArrayHasKey('type', $val);
            $this->assertArrayHasKey('name', $val);
            $this->assertArrayHasKey('seats', $val);
        }
    }


    /**
     * 获取课程列表
     */
    public function testGetCoursesApi()
    {
        $user = $this->getTeacher();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.exam.getCourses'));

        $result = json_decode($response->content(),true);

        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);

        foreach ($result['data'] as $key => $val) {

            $this->assertArrayHasKey('id', $val);
            $this->assertArrayHasKey('scores', $val);
            $this->assertArrayHasKey('year', $val);

        }
    }


    /**
     *
     */
    public function testLoadBySchoolApi()
    {
        $user = $this->getTeacher();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('api.school.load.majors',['id'=>1]));
        $result = json_decode($response->content(),true);
        dd($result);
    }


}
