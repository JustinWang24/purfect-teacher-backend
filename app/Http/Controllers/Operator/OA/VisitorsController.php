<?php

namespace App\Http\Controllers\Operator\OA;

use App\Dao\OA\VisitorDao;
use App\Http\Requests\MyStandardRequest;
use App\Http\Controllers\Controller;

class VisitorsController extends Controller
{
    public function management(MyStandardRequest $request){
        $dao = new VisitorDao();
        $this->dataForView['pageTitle'] = '来访管理';

        // 开始和结束时间
        $sdate = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
        $edate = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1);
        // 获取今天的访客
        $this->dataForView['todayVisitors'] = $dao->getTodayVisitorsBySchoolId($request->getSchoolId(), $sdate, $edate);
        $todayVisitorsCount2 = $dao->getTodayVisitorsBySchoolIdCount($request->getSchoolId(), $sdate, $edate, 2); // 已预约
        $todayVisitorsCount3 = $dao->getTodayVisitorsBySchoolIdCount($request->getSchoolId(), $sdate, $edate, 3); // 已到访
        $todayVisitorsCount1 = $todayVisitorsCount2 - $todayVisitorsCount3; // 未到访
        $this->dataForView['todayVisitorsCount'] = [
            'count1' => $todayVisitorsCount1,
            'count2' => $todayVisitorsCount2,
            'count3' => $todayVisitorsCount3
        ];
        // 获取所有访客
        $this->dataForView['visitors'] = $dao->getVisitorsBySchoolId($request->getSchoolId());

        return view('school_manager.oa.visitors', $this->dataForView);
    }
}
