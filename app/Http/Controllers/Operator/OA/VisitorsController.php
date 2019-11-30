<?php

namespace App\Http\Controllers\Operator\OA;

use App\Dao\OA\VisitorDao;
use App\Http\Requests\MyStandardRequest;
use App\Http\Controllers\Controller;

class VisitorsController extends Controller
{
    public function management(MyStandardRequest $request){
        $dao = new VisitorDao();
        $this->dataForView['pageTitle'] = '今日预约';
        $this->dataForView['visitors'] = $dao->getVisitorsBySchoolId($request->getSchoolId());
        $this->dataForView['todayVisitors'] = $dao->getTodayVisitorsBySchoolId($request->getSchoolId());
        return view('school_manager.oa.visitors', $this->dataForView);
    }
}
