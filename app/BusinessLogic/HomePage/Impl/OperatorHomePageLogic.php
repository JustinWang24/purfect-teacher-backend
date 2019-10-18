<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 7:17 PM
 */

namespace App\BusinessLogic\HomePage\Impl;
use App\BusinessLogic\HomePage\Contracts\IHomePageLogic;
use App\User;
use App\Dao\Schools\SchoolDao;

class OperatorHomePageLogic implements IHomePageLogic
{
    private $user = null;
    private $data = [];

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getDataForView()
    {
        $dao = new SchoolDao($this->user);
        /**
         * 运营人员, 那么应该显示学校的列表, 引导管理员进入某所学校
         */
        $this->data['schools'] = $dao->getMySchools();
        return $this->data;
    }
}