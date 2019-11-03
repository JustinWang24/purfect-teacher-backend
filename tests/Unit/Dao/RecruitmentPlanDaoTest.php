<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 3/11/19
 * Time: 11:09 PM
 */

namespace Tests\Unit\Dao;
use Tests\TestCase;
use App\Dao\RecruitmentPlan\RecruitmentPlanDao;
use App\Models\Schools\RecruitmentPlan;

class RecruitmentPlanDaoTest extends TestCase
{

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
}