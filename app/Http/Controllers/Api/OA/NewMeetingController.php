<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/2/10
 * Time: 下午12:54
 */

namespace App\Http\Controllers\Api\OA;


use Carbon\Carbon;
use Endroid\QrCode\QrCode;
use App\Utils\JsonBuilder;
use App\Models\Schools\Room;
use App\Dao\Schools\RoomDao;
use App\Dao\OA\NewMeetingDao;
use App\Models\OA\NewMeeting;
use App\Http\Controllers\Controller;
use App\Http\Requests\OA\MeetingRequest;

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
                'room_id'=>$item->id,
                'img' => '',
                'name' => $item->name,
                'seats' => $item->seats,
                'start' => '8:00',
                'end' => '18:00',
                'time'=>[],
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


    /**
     * 已完成会议
     * @param MeetingRequest $request
     * @return string
     */
    public function accomplish(MeetingRequest $request) {
        $userId = $request->user()->id;
        $dao = new NewMeetingDao();
        $return = $dao->accomplishMeet($userId);

        $result = pageReturn($return);
        $data = [];
        foreach ($result['list'] as $key => $item) {
            $data[] = [
                'meet_title' => $item->meet_title,
                'approve_user' => $item->approve->name,
                'room' => $item->room_id ? $item->room->name : $item->room_text,
                'meet_time' => $item->getMeetTime(),
                'signin_time' =>$item->getSignInTime(),
                'signin_status' => $item->signIn_status,
                'signout_status' => $item->signOut_status
            ];
        }
        $result['list'] = $data;
        return JsonBuilder::Success($result);
    }


    /**
     * 自己创建的
     * @param MeetingRequest $request
     * @return string
     */
    public function oneselfCreate(MeetingRequest $request) {
        $userId = $request->user()->id;
        $dao = new NewMeetingDao();
        $return = $dao->oneselfCreateMeet($userId);

        $result = pageReturn($return);
        $data = [];
        foreach ($result['list'] as $key => $item) {

            $now = Carbon::now()->toDateTimeString();
            // 通过
            if($item->status == NewMeeting::STATUS_PASS) {
                if($now < $item->meet_start) {
                    $status = NewMeeting::STATUS_WAIT;
                } elseif ($now > $item->meet_start && $now < $item->meet_end) {
                    $status = NewMeeting::STATUS_UNDERWAY;
                } else {
                    $status = NewMeeting::STATUS_FINISHED;
                }
            } else {
                $status = $item->status;
            }

            $data[] = [
                'meet_title' => $item->meet_title,
                'approve_user' => $item->approve->name,
                'room' => $item->room_id ? $item->room->name : $item->room_text,
                'meet_time' => $item->getMeetTime(),
                'signin_time' =>$item->getSignInTime(),
                'status' => $status,
            ];
        }
        $result['list'] = $data;
        return JsonBuilder::Success($result);
    }


    /**
     * 会议详情
     * @param MeetingRequest $request
     * @return string
     */
    public function meetDetails(MeetingRequest $request) {
        $meetId = $request->getMeetId();
        $dao = new NewMeetingDao();
        $info = $dao->meetDetails($meetId);
        if(is_null($info)) {
            return JsonBuilder::Error('该会议不存在');
        }

        $fields = [];
        foreach ($info->files as $item) {
            $fields[] = $item->url;
        }

        $result = [
            'meet_title' => $info->meet_title,
            'room' => $info->room_id? $info->room->name : $info->room_text,
            'meet_time' => $info->getMeetTime(),
            'approve' => $info->approve->name,
            'user_num' => $info->meetUsers->count(),
            'signin_time' => $info->getSignInTime(),
            'signout_time' => $info->getSignOutTime(),
            'meet_content' => $info->meet_content,
            'fields' => $fields
        ];
        return JsonBuilder::Success($result);
    }


    /**
     * 保存会议纪要
     * @param MeetingRequest $request
     * @return string
     */
    public function saveMeetSummary(MeetingRequest $request) {
        $meetId = $request->getMeetId();
        $summaries = $request->file('summary');
        if(count($summaries) > 7) {
            return JsonBuilder::Error('最多上传7张');
        }
        $userId = $request->user()->id;

        $dao = new NewMeetingDao();
        $result = $dao->saveMeetSummary($meetId,$userId,$summaries);
        if($result->isSuccess()) {
            return JsonBuilder::Success($result->getMessage());
        } else {
            return JsonBuilder::Error($result->getMessage());
        }
    }


    /**
     * 获取会议纪要
     * @param MeetingRequest $request
     * @return string
     */
    public function getMeetSummary(MeetingRequest $request) {
        $meetId = $request->getMeetId();
        $userId = $request->user()->id;
        $dao = new NewMeetingDao();
        $list = $dao->getMeetSummary($meetId, $userId);
        return JsonBuilder::Success($list);
    }


    /**
     * 已完成-签到记录
     * @param MeetingRequest $request
     * @return string
     */
    public function signInRecord(MeetingRequest $request) {
        $meetId = $request->getMeetId();
        $userId = $request->user()->id;
        $dao = new NewMeetingDao();
        $return = $dao->getMeetUser($meetId, $userId);
        $result = [
            'signin_status' => $return->signin_status,
            'signin_time' => $return->signin_time,
            'signout_status' => $return->signout_status,
            'signout_time' => $return->signout_time
        ];

        return JsonBuilder::Success($result);
    }


    /**
     * 签到二维码
     * @param MeetingRequest $request
     * @return string
     * @throws \Endroid\QrCode\Exception\InvalidPathException
     */
    public function signInQrCode(MeetingRequest $request) {
        $meetId = $request->getMeetId();
        $dao = new NewMeetingDao();
        $meet = $dao->getMeetByMeetId($meetId);
        if(is_null($meet)) {
            return JsonBuilder::Error('该会议不存在');
        }

        $codeStr = base64_encode(json_encode(
            ['meet_id' => $meetId,'type'=>'signIn']));

        $qrCode = new QrCode($codeStr);
        $qrCode->setSize(400);
        $qrCode->setLogoPath(public_path('assets/img/logo.png'));
        $qrCode->setLogoSize(60, 60);
        $code = 'data:image/png;base64,' . base64_encode($qrCode->writeString());

        $data = ['msg'=>'签到二维码','qrcode'=>$code];
        return JsonBuilder::Success($data);
    }



}