<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 7:22 PM
 */

namespace App\BusinessLogic\HomePage\Impl;
use App\BusinessLogic\HomePage\Contracts\IHomePageLogic;
use App\Models\School;
use App\User;
use App\Dao\Schools\SchoolDao;
use Illuminate\Http\Request;

class SchoolManagerHomePageLogic implements IHomePageLogic
{
    private $user = null;
    private $data = [];
    public $request;

    public function __construct(Request $request)
    {
        $this->user = $request->user();
        $this->request = $request;
    }

    public function getDataForView()
    {
        $dao = new SchoolDao($this->user);
        /**
         * 运营人员, 那么应该显示学校的列表, 引导管理员进入某所学校
         */
        /**
         * @var School $school
         */
        $school = $dao->getMySchools();
        $this->data['school'] = $school;
        $this->data['config'] = $school->configuration;
        $school->savedInSession($this->request);
        return $this->data;
    }


}
