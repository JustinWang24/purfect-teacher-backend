<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/3/29
 * Time: 下午2:33
 */

namespace App\Http\Controllers\Operator\OA;


use Carbon\Carbon;
use App\Dao\OA\NewMeetingDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\OA\MeetingRequest;

class NewMeetingController extends Controller
{

    /**
     * 会议列表
     * @param MeetingRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(MeetingRequest $request) {
        $schoolId = $request->getSchoolId();
        $dao = new NewMeetingDao();
        $list = $dao->getMeetingBySchoolId($schoolId);
        $this->dataForView['list'] = $list;
        $this->dataForView['now'] = Carbon::now();
        return view('school_manager.meeting.list', $this->dataForView);
    }

}