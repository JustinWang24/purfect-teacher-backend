<?php


namespace App\BusinessLogic\EnrolmentStepLogic\Impl;


use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use App\Dao\EnrolmentStep\SchoolEnrolmentStepDao;
use App\BusinessLogic\EnrolmentStepLogic\Contracts\IEnrolmentStep;

class EnrolmentStepBefore implements IEnrolmentStep
{

    private $id;
    private $schoolId;
    private $campusId;
    private $dao;

    use TEnrolmentStepInfo;
    /**
     * EnrolmentStepBefore constructor.
     * @param $id
     * @param $schoolId
     * @param $campusId
     * @param SchoolEnrolmentStepDao $dao
     */
    public function __construct($id, $schoolId, $campusId, $dao)
    {
        $this->id       = $id;
        $this->schoolId = $schoolId;
        $this->campusId = $campusId;
        $this->dao      = $dao;
    }

    public function getEnrolment()
    {
        if(is_null($this->id)) {
            // 查询学校的第一条
            return $this->dao->getEnrolmentStepAfterFirst($this->schoolId, $this->campusId);
        } else {
            $currentStep = $this->dao->getEnrolmentStepById($this->id);
            return $this->dao->getEnrolmentStepBeforeFirst($this->schoolId, $this->campusId, $currentStep->sort);
        }

    }

    public function getData()
    {
        $step = $this->getEnrolment();
        if(!is_null($step)) {
            return $this->getEnrolmentStepInfo($step);
        } else {
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'已经是第一步了',[]);
        }
    }


}
