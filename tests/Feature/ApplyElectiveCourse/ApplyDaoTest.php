<?php
namespace Tests\Feature\ApplyElectiveCourse;

use App\Dao\ElectiveCourses\TeacherApplyElectiveCourseDao;
use App\Dao\Users\UserDao;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Feature\BasicPageTestCase;

class ApplyDaoTest extends BasicPageTestCase
{


    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCanCreateApply()
    {
        $this->withoutExceptionHandling();
        $dao = new UserDao();
        $user = $dao->getUserByMobile('1000007');
        $data = self::__createApplyData();
        $response = $this->setSchoolAsUser($user, 1)
            ->actingAs($user, 'api')
            ->withSession($this->schoolSessionData)
            ->post(route('api.elective-course.save', $data));

        $result = json_decode($response->content(),true);
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals(1000, $result['code']);
        foreach ($result['data'] as $key => $val)
        {
            $this->assertEquals('id', $key);

        }
        return $val;
    }

    /**
     * @depends testCanCreateApply
     */
    public function testCanVerified($val)
    {
        $this->withoutExceptionHandling();
        $dao = new TeacherApplyElectiveCourseDao();
        $dao->approvedApply($val, '批准申请');
        $status = DB::table('teacher_apply_elective_courses')->where('id', $val)
            ->first()->status;
        $this->assertEquals(2, $status);
        return $val;
    }

    /**
     * @depends testCanVerified
     * @param $val
     * @return mixed|null
     */
    public function testCanPublish($val)
    {
        $this->withoutExceptionHandling();
        $dao = new TeacherApplyElectiveCourseDao();
        $result = $dao->publishToCourse($val);
        $status = DB::table('teacher_apply_elective_courses')->where('id', $val)
            ->first()->status;
        $this->assertEquals(4, $status);
        return $result->getData();
    }

    /**
     * @depends testCanPublish
     * @param $courseId
     * @return
     */
    public function testCanEnroll($courseId)
    {
        $this->withoutExceptionHandling();
        $userDao = new UserDao();
        $user = $userDao->getUserByMobile('1000002');
        $dao = new TeacherApplyElectiveCourseDao();
        $result = $dao->enroll($courseId, $user->id, 902, 1);
        $enroll = DB::table('student_enrolled_optional_courses')->where('course_id','=',$courseId)
        ->where('user_id', $user->id)->first();
        $this->assertEquals($result->id, $enroll->id);
        return $courseId;
    }

    /**
     * @depends testCanEnroll
     * @param $courseId
     */
    public function testFlushAll($courseId)
    {
        DB::table('teacher_apply_elective_courses')->where('teacher_id','=',902)->delete();
        DB::table('courses')->where('name','=','基础物理')->delete();
        DB::table('student_enrolled_optional_courses')->where('course_id','=',$courseId)->delete();
        DB::table('student_enrolled_optional_courses')->where('teacher_id','=',902)->delete();
        $this->assertEquals(1,1);
    }

    public function __createApplyData()
    {
        return json_decode('{"course":{"code":"第一册","name":"基础物理","scores":"10","majors":[17,40],"optional":"1","year":1,"term":1,"desc":"一门理科专业必须掌握的课程","open_num":"1","teacher_id":902,"teacher_name":"Ashleigh Koelpin","max_number":"2"},"schedule":[{"weeks":[1,2,3,4],"days":[4],"timeSlots":[18],"building_id":1,"building_name":"教学主楼","classroom_id":1,"classroom_name":"1层001号"}],"version":"3.0"}',1);
    }
}
