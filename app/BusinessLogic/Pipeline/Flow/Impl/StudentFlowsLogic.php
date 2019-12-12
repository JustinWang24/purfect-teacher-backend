<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 7/12/19
 * Time: 5:07 PM
 */

namespace App\BusinessLogic\Pipeline\Flow\Impl;

use App\User;
use App\Dao\Pipeline\FlowDao;
use App\Utils\Pipeline\IFlow;

class StudentFlowsLogic extends GeneralFlowsLogic
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function getMyFlows($forApp = false)
    {
        $dao = new FlowDao();
        return $dao->getGroupedFlows(
            $this->user->getSchoolId(), [IFlow::TYPE_STUDENT_ONLY, IFlow::TYPE_FINANCE], $forApp
        );
    }
}