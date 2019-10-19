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
        $school = $dao->getSchoolByUuid($request->uuid());
        // 获取学校
        $request->session()->put('school.id',$school->id);
        $request->session()->put('school.uuid',$school->uuid);
        $request->session()->put('school.name',$school->name);
        return redirect()->route('operator.school.view');
    }
}
