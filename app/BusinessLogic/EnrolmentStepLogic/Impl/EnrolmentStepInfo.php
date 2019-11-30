<?php


namespace App\BusinessLogic\EnrolmentStepLogic\Impl;


use App\Dao\EnrolmentStep\SchoolEnrolmentStepDao;
use App\BusinessLogic\EnrolmentStepLogic\Contracts\IEnrolmentStep;

class EnrolmentStepInfo implements IEnrolmentStep
{

    use TEnrolmentStepInfo;
    private $id;
    private $dao;

    /**
     * EnrolmentStepInfo constructor.
     * @param $id
     * @param SchoolEnrolmentStepDao $dao
     */
    public function __construct($id,$dao)
    {
        $this->id = $id;
        $this->dao = $dao;
    }

    public function getEnrolment()
    {
        return $this->dao->getEnrolmentStepById($this->id);
    }

    public function getData()
    {
        $step = $this->getEnrolment();
        $result = $this->getEnrolmentStepInfo($step);
        return $result;
    }


}
