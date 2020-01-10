<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/1/10
 * Time: 上午9:19
 */

namespace App\Http\Controllers\Api\Conferences;


use App\Dao\Schools\RoomDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Models\Schools\Room;
use App\Utils\JsonBuilder;
use Carbon\Carbon;

class MeetingController extends Controller
{

    public function setting(MyStandardRequest $request) {
        $date = $request->get('date', Carbon::now()->toDateString());
        $schoolId = $request->user()->getSchoolId();
        $dao = new RoomDao();
        // 会议室
        $map = ['school_id'=>$schoolId, 'type'=>Room::TYPE_MEETING_ROOM];
        $rooms = $dao->getRooms($map);
        if(count($rooms) == 0) {
            return JsonBuilder::CODE_ERROR('暂无会议室');
        }

        // 查询会议当天使用的时间



    }
}