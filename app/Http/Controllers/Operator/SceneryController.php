<?php


namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Dao\Schools\SchoolResourceDao;
use Illuminate\Http\Request;

class SceneryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 学校风采管理
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list(Request $request)
    {
        $schoolId = 1;

        $dao = new SchoolResourceDao;

        $list = $dao->getSchoolResourceBySchoolIdOrUuid($schoolId);

        $this->dataForView['data']      =  $list;
        $this->dataForView['pageTitle'] = '学校风采管理';
        return view('school_manager.scenery.list', $this->dataForView);
    }

}
