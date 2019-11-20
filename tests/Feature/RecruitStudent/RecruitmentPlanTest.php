<?php

namespace Tests\Feature\RecruitStudent;

use App\Dao\RecruitStudent\RegistrationInformaticsDao;
use App\Events\User\Student\ApplyRecruitmentPlanEvent;
use App\Events\User\Student\ApproveRegistrationEvent;
use Tests\Feature\BasicPageTestCase;

class RecruitmentPlanTest extends BasicPageTestCase
{

    /**
     * 测试正确获取可以报名的专业
     */
    public function testItCanGetOpenMajorApi()
    {
        $this->withoutExceptionHandling();
        $data = ['school_id' => '1', 'id_number' => '201928128038509'];
        $response = $this->post(route('api.load.open.majors'), $data, $this->getHeaderWithApiToken());
        $result = json_decode($response->content(),true);
        $this->assertTrue(1===2);
    }

    /**
     * 测试正确获取专业详情
     */
    public function testItCanGetMajorDetailApi()
    {
        $this->withoutExceptionHandling();
        $su = $this->getSchoolManager();
        $data = ['id' => '2'];
        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('api.load.major.detail'), $data);
         $this->assertTrue(1===2);
    }


    /**
     * 测试正确获取已报名学生, 辅助填充数据
     */
    public function testItCanGetQueryStudentProfileApi()
    {
        $this->withoutExceptionHandling();
        $data = ['id_number' => '201928128038509'];
        $response = $this->post(route('api.query.student.profile'), $data, $this->getHeaderWithApiToken());
        $this->assertTrue(1===2);
    }

    /**
     * 测试正确提添加学生报名
     */
    public function testItCanSubmitFormApi()
    {
        $this->withoutExceptionHandling();
        $su = $this->getSchoolManager();
        $data = ['id' => '', 'major_id' => '1', 'school_id' => '1' , 'recruitment_plan_id' => '1', 'data' => ['name' => '123', 'mobile' => 12211201202,
                                                           'gender' => 1, 'parent_name' => '帕菲特',
                                                           'parent_mobile' => '110', 'relocation_allowed' => '1']
            ];

        $response = $this->setSchoolAsUser($su, 1)
            ->actingAs($su)
            ->withSession($this->schoolSessionData)
            ->post(route('api.major.submit.form'), $data);
         $this->assertTrue(1===2);
    }


    public function testApplyRecruitmentPlanEvent() {

        $dao = new RegistrationInformaticsDao();
        $info = $dao->getInformaticsByUserIdAndPlanId(1,1);
        event(new ApplyRecruitmentPlanEvent($info));
    }


    public function testApproveRegistrationEvent() {
        $dao = new RegistrationInformaticsDao();
        $info = $dao->getInformaticsByUserIdAndPlanId(1,1);
        event(new ApproveRegistrationEvent($info));
    }

}
