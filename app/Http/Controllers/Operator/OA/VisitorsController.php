<?php

namespace App\Http\Controllers\Operator\OA;

use App\Dao\OA\VisitorDao;
use App\Http\Requests\MyStandardRequest;
use App\Http\Controllers\Controller;

class VisitorsController extends Controller
{
    /**
     * Func 来访列表
     *
     * @param MyStandardRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function management(MyStandardRequest $request)
    {
        $param['status'] = $request->input('status', '');
        $param['keywords'] = $request->input('keywords', '');
        $param['startDate'] = $request->input('startDate', '');
        $param['endDate'] = $request->input('endDate', '');

        // 开始和结束时间
        $sdate = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
        $edate = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1);

        // 获取今天的访客
        $dao = new VisitorDao();
        $this->dataForView['todayVisitors'] = $dao->getTodayVisitorsBySchoolId($request->getSchoolId(), $sdate, $edate);
        $todayVisitorsCount2 = $dao->getTodayVisitorsBySchoolIdCount($request->getSchoolId(), $sdate, $edate, [2]);
        $todayVisitorsCount3 = $dao->getTodayVisitorsBySchoolIdCount($request->getSchoolId(), $sdate, $edate, [3]);
        $this->dataForView['todayVisitorsCount'] = [
            'count1' => $todayVisitorsCount2 + $todayVisitorsCount3, // 已预约
            'count2' => $todayVisitorsCount2, // 未到访
            'count3' => $todayVisitorsCount3 // 已到访
        ];

        // 获取所有访客
        $this->dataForView['pageTitle'] = '来访管理';
        $this->dataForView['visitors'] = $dao->getVisitorsBySchoolId($request->getSchoolId(), $param);

        return view('school_manager.oa.visitors', $this->dataForView);
    }
}
