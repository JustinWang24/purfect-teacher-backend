<?php
namespace Tests\Feature\TeacherPages;

use App\Models\Teachers\ExamsPlan;
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
    public function _createData()
    {
        $data = [
            'name'=>'数学考试',
            'course_id'=>'1',     // 课程ID
            'semester' => 1,      // 学期
//            'formalism'=> Exam::FORMALISM_WRITTEN,   // 考试形式
//            'type'     => Exam::TYPE_MIDTERM,        // 类型
//            'room_id'  => [1,2,3],      // 房间ID
//            'from'     => Carbon::tomorrow()->format('Y-m-d 14:00:00'),
//            'to'       => Carbon::tomorrow()->format('Y-m-d 16:00:00'),
        ];
        return $data;
    }


    /**
     * 添加考试
     */
    public function testCreateExamApi()
    {
        $data = $this->_createData();

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
     * 获取学校下面的系
     */
    public function testGetDepartmentListApi()
    {
        $user = $this->getTeacher();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.exam.getDepartmentList'));
        $result = json_decode($response->content(),true);

        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
        foreach ($result['data'] as $key => $val)
        {
             $this->assertArrayHasKey('id', $val);
             $this->assertArrayHasKey('school_id', $val);
             $this->assertArrayHasKey('name', $val);
             $this->assertArrayHasKey('institute_id', $val);
        }
    }


    /**
     * 获取系下面的专业
     */
    public function testGetMajorListApi()
    {
        $user = $this->getTeacher();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.exam.getMajorList',['department_id'=>1472]));
        $result = json_decode($response->content(),true);

        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
        foreach ($result['data'] as $key => $val)
        {
             $this->assertArrayHasKey('id', $val);
             $this->assertArrayHasKey('school_id', $val);
             $this->assertArrayHasKey('name', $val);
        }
    }


    /**
     * 获取专业下的班
     */
    public function testGetGradeListApi()
    {
        $user = $this->getTeacher();
        $data = ['major_id'=>2943,'year'=>2019];
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.exam.getGradeList',$data));
        $result = json_decode($response->content(),true);

        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
        foreach ($result['data'] as $key => $val)
        {
             $this->assertArrayHasKey('id', $val);
             $this->assertArrayHasKey('year', $val);
             $this->assertArrayHasKey('name', $val);
        }
    }


    /**
     * 创造考试计划数据
     * @return array
     */
    public function _createExamPlanData()
    {
        $data = [
            'exam_id'      => 1,
            'campus_id'    => 250,
            'institute_id' => 500,
            'department_id'=> 1472,
            'major_id'     => 2943,
            'year'         => 2019,
            'grade_id'     => 23541,
            'type'         => ExamsPlan::TYPE_FINAL,
            'formalism'    => ExamsPlan::FORMALISM_WRITTEN,
            'from'         => Carbon::tomorrow()->format('Y-m-d 14:00:00'),
            'to'           => Carbon::tomorrow()->format('Y-m-d 16:00:00'),
        ];

        return $data;
    }


    /**
     * 创建考试计划
     */
    public function testCreateExamPlanApi() {
        $data = $this->_createExamPlanData();

        $user = $this->getTeacher();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.exam.createExamPlan',$data));

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);
    }


    /**
     * 查询空闲的教室
     */
    public function testGetLeisureRoomApi() {
        $data = [
            'campus_id'=>250,
            'from'=>'2019-10-30 08:00:00',
            'to'=>'2019-10-30 12:00:00'
        ];
        $user = $this->getTeacher();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.exam.getLeisureRoom',$data));

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(1000, $result['code']);

        if (!empty($result['data'])) {

            foreach ($result['data'] as $key => $val) {
                 $this->assertArrayHasKey('id', $val);
                 $this->assertArrayHasKey('name', $val);
            }
        }

    }



    /**
     * 创建考点
     */
    public function testCreatePlanRoomApi()
    {
        $data = [
            'plan_id' => 2,
            'room_id' => [2,3],
        ];

        $user = $this->getTeacher();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.exam.createPlanRoom',$data));

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);

    }


    /**
     * 考点绑定老师
     */
    public function testRoomBindingTeacherApi()
    {
        $data = [
            'plan_room_id'=>13,
            'first_teacher_id'=>1,
            'second_teacher_id'=>2,
            'thirdly_teacher_id'=>3,
            'first_invigilate'=>'小张',
            'second_invigilate'=>'小明',
            'thirdly_invigilate'=>'小李'
        ];

        $user = $this->getTeacher();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('teacher.exam.roomBindingTeacher',$data));
        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
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
    public function testGetCoursesApi() {
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
