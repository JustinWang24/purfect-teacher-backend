<?php

namespace App\Http\Controllers\Operator;

use App\Http\Requests\SchoolRequest;
use App\Http\Controllers\Controller;
use App\Dao\Schools\SchoolDao;
use App\Models\School;
use App\Utils\FlashMessageBuilder;

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

    public function config_update(SchoolRequest $request){
        $dao = new SchoolDao($request->user());
        $school = $dao->getSchoolByUuid($request->uuid());
        // 要比较学校中每个系的相同的配置项目, 如果学校的要求高于系的要求, 那么就要覆盖系的. 如果低于系的要求, 那么就保留
        if($school){
            $dao->updateConfiguration($request->getConfiguration(), $school);
            FlashMessageBuilder::Push($request, 'success','配置已更新');
        }
        else{
            FlashMessageBuilder::Push($request, 'danger','无法获取学校数据');
        }
        return redirect()->route('school_manager.school.view');
    }
}
