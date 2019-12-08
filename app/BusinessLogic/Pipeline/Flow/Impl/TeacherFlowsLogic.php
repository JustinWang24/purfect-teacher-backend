<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 4/12/19
 * Time: 8:49 PM
 */

namespace App\BusinessLogic\Pipeline\Flow\Impl;

use App\Dao\Pipeline\FlowDao;
use App\Models\Teachers\Teacher;
use App\User;
use App\Utils\Pipeline\IFlow;

class TeacherFlowsLogic extends GeneralFlowsLogic
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * 获取教师的专用流程
     * @return IFlow[]|array
     */
    public function getMyFlows()
    {
        $dao = new FlowDao();
        return $dao->getGroupedFlows(
            $this->user->getSchoolId(),Teacher::FlowTypes()
        );
    }
}