<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 3/11/19
 * Time: 11:09 PM
 */

namespace Tests\Unit\Dao;
use App\Dao\RecruitStudent\RegistrationInformaticsDao;
use App\Models\RecruitStudent\RegistrationInformatics;
use Tests\TestCase;
use App\Dao\RecruitmentPlan\RecruitmentPlanDao;
use App\Models\Schools\RecruitmentPlan;
use App\User;

class RecruitmentPlanDaoTest extends TestCase
{
    private $testFormId = 4;

    /**
     * 创建方法的测试
     */
    public function testItCanCreateThenDeletePlan(){
        $planData = $this->_createMockData(RecruitmentPlan::class, true);

        $dao = new RecruitmentPlanDao(1);
        $plan = $dao->createPlan($planData);
        $this->assertNotNull($plan);

        $this->assertNotNull($plan->school);
        $this->assertNotNull($plan->major);
        $this->assertNotNull($plan->manager);

        $rows = $dao->deletePlan($plan->id, true);
        $this->assertEquals(1, $rows);
    }

    /**
     * 更新和删除工作
     */
    public function testItCanUpdateThenDeletePlan(){
        $planData = $this->_createMockData(RecruitmentPlan::class, true);

        $dao = new RecruitmentPlanDao(1);
        $plan = $dao->createPlan($planData);

        $planId = $plan->id;

        $updatedPlanData = $this->_createMockData(RecruitmentPlan::class, true);
        $updatedPlanData['id'] = $planId;

        $updated = $dao->updatePlan($updatedPlanData);
        $this->assertNotFalse($updated);

        $thePlan = $dao->getPlan($planId);

        $this->assertEquals($updatedPlanData['title'],$thePlan->title);

        $deleted = $dao->deletePlan($planId);
        $this->assertEquals(1, $deleted);
    }

    /**
     * 测试可以批准
     */
    public function testItCanApprove(){
        $dao = new RegistrationInformaticsDao();
        $manager = User::find(1);
        $bag = $dao->approve($this->testFormId, $manager, 'super');
        $this->assertTrue($bag->isSuccess());
        $form = RegistrationInformatics::find($this->testFormId);
        $this->assertEquals(RegistrationInformatics::PASSED, $form->status);
        $form->status = RegistrationInformatics::WAITING;
        $form->save();

        $manager = User::find(10); // 找一个老师
        $bag = $dao->approve($this->testFormId, $manager, 'super');
        $this->assertTrue($bag->isSuccess());
        $form = RegistrationInformatics::find($this->testFormId);
        $this->assertEquals(RegistrationInformatics::PASSED, $form->status);
        $form->status = RegistrationInformatics::WAITING;
        $form->save();
    }

    /**
     * 测试不能批准
     */
    public function testItCanNotApprove(){
        $dao = new RegistrationInformaticsDao();
        /**
         * @var User $manager
         */
        $manager = User::find(4);
        $bag = $dao->approve($this->testFormId, $manager, 'hack');
        $this->assertFalse($bag->isSuccess());

        $manager = User::find(168004); // 不是通一个学校的老师
        $bag = $dao->approve($this->testFormId, $manager, 'hack');
        $this->assertFalse($bag->isSuccess());
    }

    /**
     * 测试可以拒绝申请
     */
    public function testItCanReject(){
        $dao = new RegistrationInformaticsDao();
        $manager = User::find(1);
        $bag = $dao->reject($this->testFormId, $manager, 'super');
        $this->assertTrue($bag->isSuccess());
        $form = RegistrationInformatics::find($this->testFormId);
        $this->assertEquals(RegistrationInformatics::REJECTED, $form->status);
        $form->status = RegistrationInformatics::WAITING;
        $form->save();

        $manager = User::find(10); // 找一个老师
        $bag = $dao->reject($this->testFormId, $manager, 'teacher');
        $this->assertTrue($bag->isSuccess());
        $form = RegistrationInformatics::find($this->testFormId);
        $this->assertEquals(RegistrationInformatics::REJECTED, $form->status);
        $form->status = RegistrationInformatics::WAITING;
        $form->save();
    }

    /**
     * 测试不能执行拒绝操作, 因为权限不够
     */
    public function testItCanNotReject(){
        $dao = new RegistrationInformaticsDao();
        /**
         * @var User $manager
         */
        $manager = User::find(4);
        $bag = $dao->reject($this->testFormId, $manager, 'hack');
        $this->assertFalse($bag->isSuccess());

        $manager = User::find(168004); // 不是通一个学校的老师
        $bag = $dao->reject($this->testFormId, $manager, 'hack');
        $this->assertFalse($bag->isSuccess());
    }
}