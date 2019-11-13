<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/10/19
 * Time: 2:20 PM
 */

namespace App\BusinessLogic\HomePage\Impl;

use App\BusinessLogic\HomePage\Contracts\IHomePageLogic;
use App\Dao\Schools\SchoolDao;
use App\User;
use Illuminate\Http\Request;

class SuHomePageLogic implements IHomePageLogic
{
    private $user = null;
    private $data = [];

    public function __construct(Request $request)
    {
        $this->user = $request->user();
    }

    public function getDataForView()
    {
        $dao = new SchoolDao($this->user);
        /**
         * 超级管理员, 那么应该显示学校的列表, 引导管理员进入某所学校
         */
        $this->data['schools'] = $dao->getMySchools();
        return $this->data;
    }
}
