<?php


namespace App\Http\Controllers\Api\OA;


use Carbon\Carbon;
use App\Dao\OA\MeetingDao;
use App\Models\OA\Meeting;
use App\Utils\JsonBuilder;
use App\Models\OA\MeetingUser;
use App\Dao\OA\GroupMemberDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\OA\MeetingRequest;

class MeetIngController extends Controller
{
    /**
     * 创建会议
     * @param MeetingRequest $request
     * @return string
     */
    public function addMeeting(MeetingRequest $request) {
        $data = $request->all();
        $data['user_id'] = $request->user()->id;
        $data['school_id'] = $request->user()->getSchoolId();

        $userId = [];
        if(!empty($data['useridstr'])) {
            $userId = explode(',',$data['useridstr']);
        }
        unset($data['useridstr']);
        // 查询组成员
        if(!empty($data['groupidstr'])) {
            $groupId = explode(',', $data['groupidstr']);
            $groupMemberDao = new GroupMemberDao();
            $members = $groupMemberDao->getGroupMemberByGroupIds($groupId);
            $userIdArr = $members->pluck('user_id')->toArray();
            $userId = array_unique(array_merge($userId, $userIdArr));
        }

        $dao = new MeetingDao();
        $result = $dao->addMeeting($data, $userId);
        if($result->isSuccess()) {
            return JsonBuilder::Success($result->getData());
        } else {
            return JsonBuilder::Error($result->getMessage());
        }
    }

    /**
     * 待签列表
     * @param MeetingRequest $request
     * @return string
     */
    public function todoList(MeetingRequest $request) {
        $user = $request->user();
        $dao = new MeetingDao();
        $result = $dao->todoList($user);
        $list = $result->getCollection();
        $data = [];
        $now = Carbon::now()->toDateTimeString();
        foreach ($list as $key => $item) {
            $data[$key] = $this->dataDispose($item);
            $data[$key]['user_username'] = $item->user->name;
            $data[$key]['button_status'] = 'signin';
            $data[$key]['button_tip'] = 'normal';

            if($item->my_signin_status == MeetingUser::SIGN_IN_LATE) {
                $data[$key]['button_status'] = 'signin';
                if($now > $item->meet_start) {
                    $data[$key]['button_tip'] = 'late'; // 迟到
                }
            } elseif ($item->my_signout_status == MeetingUser::SIGN_OUT_EARLY) {
                $data[$key]['button_status'] = 'signout';
                if($item->signout_status == Meeting::SIGN_OUT && $now < $item->signout_start ) {
                    $data[$key]['button_tip'] = 'early'; // 早退
                }
            }
        }

        return JsonBuilder::Success($data);
    }

    /**
     * 已完成列表
     * @param MeetingRequest $request
     * @return string
     */
    public function doneList(MeetingRequest $request) {
        $user = $request->user();
        $dao = new MeetingDao();
        $result = $dao->doneList($user);
        $list = $result->getCollection();
        $data = [];
        foreach ($list as $key => $item) {
            $data[$key] = $this->dataDispose($item);
            $data[$key]['user_username'] = $item->user->name;
            $data[$key]['button_txt'] = '正常';
            if($item->my_signin_status == MeetingUser::SIGN_IN_LATE) {
                $data[$key]['button_txt'] = '迟到';
            }
            if($item->my_signout_status == MeetingUser::SIGN_OUT_EARLY && $item->signout_status == Meeting::SIGN_OUT) {
                $data[$key]['button_txt'] = '早退';
            }
        }

        return JsonBuilder::Success($data);
    }


    /**
     * 创建的列表
     * @param MeetingRequest $request
     * @return string
     */
    public function myList(MeetingRequest $request) {
        $user = $request->user();
        $dao = new MeetingDao();
        $result = $dao->myList($user);
        $list = $result->getCollection();
        $data = [];
        $now = Carbon::now()->toDateTimeString();
        foreach ($list as $key => $item) {
            $data[$key] = $this->dataDispose($item);
            $data[$key]['user_username'] = $item->user->name;
            if($item->meet_start > $now) {
                $data[$key]['button_txt'] = '未开始';
            } elseif ($item->meet_start <= $now && $item->meet_end >= $now) {
                $data[$key]['button_txt'] = '进行中';
            } elseif ($item->meet_end < $now) {
                $data[$key]['button_txt'] = '已结束';
            }

        }
        return JsonBuilder::Success($data);
    }


    /**
     * @param  $meeting
     * @return array
     */
    public function dataDispose($meeting) {
        $signinStart = Carbon::parse($meeting->signin_start);
        $signinEnd = Carbon::parse($meeting->signin_end)->format('H:i');
        $signin = $signinStart->format('Y-m-d').' '.$signinStart->format('H:i').'-'.$signinEnd;

        $meetStart = Carbon::parse($meeting->meet_start);
        $meetEnd = Carbon::parse($meeting->meet_end)->format('H:i');
        $meet = $meetStart->format('Y-m-d').' '.$meetStart->format('H:i').'-'.$meetEnd;

        $data = [
            'meetid' => $meeting->id,
            'meet_title' => $meeting->meet_title,
            'meet_content' => $meeting->meet_content,
            'meet_address' => $meeting->meet_address,
            'signin_transtime' => $signin,
            'meet_transtime' => $meet,
        ];
        return $data;
    }





    /**
     * 签到签退
     * @param MeetingRequest $request
     * @return string
     */
    public function qrcode(MeetingRequest $request) {
        $userId = $request->user()->id;
        $meetId = $request->getMeetId();
        $type = $request->getType();
        $dao = new MeetingDao();
        $result = $dao->signIn($userId, $meetId, $type);
        if($result->isSuccess()) {
            return JsonBuilder::Success($result->getMessage());
        } else {
            return JsonBuilder::Error($result->getMessage());
        }
    }


    /**
     * 会议详情-参与者
     * @param MeetingRequest $request
     * @return string
     */
    public function meetingMember(MeetingRequest $request) {
        $meetId = $request->getMeetId();
        $dao = new MeetingDao();
        $meeting = $dao->getMeetIngByMeetId($meetId);
        if(is_null($meeting)) {
            return JsonBuilder::Error('该会议不存在');
        }

        $data = $this->dataDispose($meeting);

        $members = $meeting->member;
        $list = [];
        foreach ($members as $key => $item) {
            $list[$key] = $data;
            $list[$key]['user_username'] = $item->user->name;
            $list[$key]['signout_status'] = $meeting->signout_status;
            $list[$key]['my_signin_status'] = $item->my_signin_status;
            $list[$key]['my_signin_time'] = $item->start;
            $list[$key]['my_signout_status'] = $item->my_signout_status;
            $list[$key]['my_signout_time'] = $item->end;
            $list[$key]['approvesid'] = 0;
        }

        return JsonBuilder::Success($list);

    }

    /**
     * 会议详情-创建者
     * @param MeetingRequest $request
     * @return string
     */
    public function minfo(MeetingRequest $request) {
        $meetId = $request->getMeetId();
        $dao = new MeetingDao();
        $meeting = $dao->getMeetIngByMeetId($meetId);
        if(is_null($meeting)) {
            return JsonBuilder::Error('该会议不存在');
        }

        $data = $this->dataDispose($meeting);
        $data['user_username'] = $meeting->user->name;
        $data['signout_transtime'] = $meeting->signout_start;
        $data['signout_status'] = $meeting->signout_status;
        $data['signin_qrcode'] = '';
        $data['self_scan'] = '';
        return JsonBuilder::Success($data);
    }


    /**
     * 签到记录
     * @param MeetingRequest $request
     * @return string
     */
    public function signLog(MeetingRequest $request) {
        $meetId = $request->getMeetId();
        $dao = new MeetingDao();
        $meeting = $dao->getMeetIngByMeetId($meetId);
        if(is_null($meeting)) {
            return JsonBuilder::Error('该会议不存在');
        }
        $members = $meeting->member;
        $list = [];
        foreach ($members as $key => $item) {
            $list[$key]['user_username'] = $item->user->name;
            $list[$key]['user_pics'] = $item->user->profile->avatar;
            $list[$key]['signin_status'] = $item->my_signin_status;
            $list[$key]['signout_status'] = $item->my_signout_status;
            $list[$key]['is_leave'] = $item->my_signout_status;
        }
        $data = [
            "count_1" => $members->where('my_signin_status',MeetingUser::UN_SIGN_IN)->count(), // ---未签到人数
            "count_2" => $members->where('my_signin_status',MeetingUser::SIGN_OUT)->count(), // ---按时签到人数
            "count_3" => $members->where('my_signin_status',MeetingUser::SIGN_IN_LATE)->count(),// ---迟到签到人数
            "count_4" => $members->where('my_signout_status',MeetingUser::UN_SIGN_OUT)->count(),// ---未签退人数
            "count_5" => $members->where('my_signout_status',MeetingUser::SIGN_OUT)->count(),// ---按时签退人数
            "count_6" => $members->where('my_signout_status',MeetingUser::SIGN_OUT_EARLY)->count(),// ---早退签退人数
            "count_7" => $members->where('is_leave',MeetingUser::LEAVE)->count(),// ---请假人数
            "list" => $list
        ];

        return JsonBuilder::Success($data);

    }



}
