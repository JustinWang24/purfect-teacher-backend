<?php

namespace App\Http\Controllers\Operator;

use App\Http\Requests\SchoolRequest;
use App\Http\Controllers\Controller;
use App\Dao\Schools\SchoolDao;
use App\Models\School;

class SchoolsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 管理员选择某个学校作为操作对象
     * @param SchoolRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enter(SchoolRequest $request){
        $dao = new SchoolDao($request->user());
        // 获取学校
        $school = $dao->getSchoolByUuid($request->uuid());
        $school->savedInSession($request);
        return redirect()->route('school_manager.school.view');
    }
}
