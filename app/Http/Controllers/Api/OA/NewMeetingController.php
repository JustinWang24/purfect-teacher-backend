<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/2/10
 * Time: 下午12:54
 */

namespace App\Http\Controllers\Api\OA;


use App\Dao\OA\NewMeetingDao;
use App\Dao\Schools\RoomDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\OA\MeetingRequest;
use App\Models\Schools\Room;
use App\Utils\JsonBuilder;
use Carbon\Carbon;

class NewMeetingController extends Controller
{

    /**
     * 会议设置
     * @param MeetingRequest $request
     * @return string
     */
    public function meetingSet(MeetingRequest $request) {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $date = $request->get('date',Carbon::now()->toDateString());

        $roomDao = new RoomDao();
        $map = ['school_id'=>$schoolId, 'type' =>Room::TYPE_MEETING_ROOM];
        $rooms = $roomDao->getRooms($map);
        $dao = new NewMeetingDao();
        $return = $dao->getMeetingByDate($schoolId, $date);
        $result = [];
        foreach ($rooms as $key => $item) {
            $result[$key] = [
                'img' => '',
                'name' => $item->name,
                'seats' => $item->seats,
                'start' => '8:00',
                'end' => '18:00',
            ];
            foreach ($return as $k => $v) {
                if($item->id == $v->room_id) {
                    $result[$key]['time'][$k]['meet_start'] = $v->meet_start;
                    $result[$key]['time'][$k]['meet_end'] = $v->meet_end;
                }
            }

        }
        return JsonBuilder::Success(array_merge($result));
    }


    /**
     * 创建会议
     * @param MeetingRequest $request
     * @return string
     */
    public function addMeeting(MeetingRequest $request) {
        $data = $request->all();
        $data['user_id'] = $request->user()->id;
        $data['school_id'] = $request->user()->getSchoolId();
        $user = $data['user'];
        unset($data['user']);
        unset($data['file']);
        $file = $request->file('file');
        $dao = new NewMeetingDao();

        array_push($user, $data['approve_userid']);
        $user = array_unique($user);

        $result = $dao->addMeeting($data, $user, $file);

        if($result->isSuccess()) {
            return JsonBuilder::Success($result->getData());
        } else {
            return JsonBuilder::Error($result->getMessage());
        }
    }


    /**
     * 待完成会议
     * @param MeetingRequest $request
     * @return string
     */
    public function unfinished(MeetingRequest $request) {
        $userId = $request->user()->id;
        $dao = new NewMeetingDao();

        $return = $dao->unfinishedMeet($userId);
        $result = pageReturn($return);
        $data = [];
        foreach ($result['list'] as $key => $item) {
            $data[] = [
                'meet_title' => $item->meet_title,
                'approve_user' => $item->approve->name,
                'room' => $item->room_id ? $item->room->name : $item->room_text,
                'meet_time' => $item->getMeetTime(),
                'signin_time' =>$item->getSignInTime(),
                'signin_status' => $item->status
            ];
        }
        $result['list'] = $data;
        return JsonBuilder::Success($result);
    }
}