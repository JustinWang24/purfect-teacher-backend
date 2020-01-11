<?php


namespace App\Dao\OA;


use App\User;
use Carbon\Carbon;
use App\Models\OA\Meeting;
use App\Utils\JsonBuilder;
use App\Models\OA\MeetingUser;
use Illuminate\Support\Facades\DB;
use App\Utils\ReturnData\MessageBag;
use App\Utils\Misc\ConfigurationTool;

class MeetingDao
{

    /**
     * 创建会议
     * @param $data
     * @param $userIdArr
     * @return MessageBag
     */
    public function addMeeting($data, $userIdArr) {
        $messageBag = new MessageBag();

        $re = $this->getMeetingByTitle($data['meet_title'],$data['school_id']);
        if(!is_null($re)) {
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
            $messageBag->setMessage('该标题会议已存在,请更换标题');
            return $messageBag;
        }

        try{
            DB::beginTransaction();
            $meeting = Meeting::create($data);

            foreach ($userIdArr as $key => $item) {
                $user = [
                    'school_id' => $data['school_id'],
                    'meetid'    => $meeting->id,
                    'user_id'   => $item,
                ];
                MeetingUser::create($user);
            }

            DB::commit();
            $messageBag->setData(['meetid'=>$meeting->id]);

        }catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
            $messageBag->setMessage($msg);
        }

        return $messageBag;
    }

    /**
     * @param $title
     * @param $schoolId
     * @return mixed
     */
    public function getMeetingByTitle($title, $schoolId) {
        $map = ['school_id'=>$schoolId, 'meet_title'=>$title];
        return Meeting::where($map)->first();
    }


    /**
     * 根据ID查询
     * @param $meetId
     * @return mixed
     */
    public function getMeetIngByMeetId($meetId) {
        return Meeting::where('id', $meetId)->first();
    }


    /**
     * 待签列表
     * @param User $user
     * @return mixed
     */
    public function todoList(User $user) {
        $now = Carbon::now()->toDateTimeString();
        $map = [
            ['oa_meeting_users.user_id', '=', $user->id],
            ['oa_meetings.signin_end', '>', $now],
            ['oa_meeting_users.my_signin_status', 'eq', MeetingUser::UN_SIGN_IN]
        ];
        $where = [
            ['oa_meeting_users.user_id', '=', $user->id],
            ['oa_meetings.signin_end', '>', $now],
            ['oa_meeting_users.my_signout_status', 'eq', MeetingUser::UN_SIGN_OUT],
            ['oa_meetings.signout_status', 'eq', Meeting::SIGN_OUT],
        ];

        return MeetingUser::join('oa_meetings',function ($join) use ($map,$where) {
            $join->on('oa_meetings.id', '=', 'oa_meeting_users.meetid')->where($map)->orWhere($where);
        })->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);

    }


    /**
     * 已完成列表
     * @param User $user
     * @return mixed
     */
    public function doneList(User $user) {
        $now = Carbon::now()->toDateTimeString();
        $map = [
            ['oa_meeting_users.user_id', '=', $user->id],
            ['oa_meetings.meet_end', '<', $now],
        ];

        return MeetingUser::join('oa_meetings',function ($join) use ($map) {
            $join->on('oa_meetings.id', '=', 'oa_meeting_users.meetid')->where($map);
        })->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);

    }

    /**
     * 创建列表
     * @param User $user
     * @return mixed
     */
    public function myList(User $user) {
        $map = ['user_id'=>$user->id];
        return Meeting::where($map)->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }


    /**
     * 签到或签退
     * @param $userId
     * @param $meetId
     * @param $type
     * @return MessageBag
     */
    public function signIn($userId, $meetId, $type) {
        $messageBag = new MessageBag();
        $map = ['user_id'=>$userId, 'meetid'=>$meetId];
        $meet = MeetingUser::where($map)->first();
        if(is_null($meet)) {
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
            $messageBag->setMessage('该会议不存在');
            return $messageBag;
        }
        $now = Carbon::now()->toDateTimeString();
        $data = [];
        if($type == 'signin') {
            $msg = '签到';
            $status = MeetingUser::SIGN_IN;
            // 当前时间大于会议开始时间 迟到
            if($now > $meet->meeting->meet_start ) {
                $status = MeetingUser::SIGN_IN_LATE;
            }
            $data = ['my_signin_status'=>$status, 'start'=>$now];
        } elseif($type == 'signout') {
            $msg = '签退';
            if($meet->my_signin_status == MeetingUser::UN_SIGN_IN) {
                $messageBag->setCode(JsonBuilder::CODE_ERROR);
                $messageBag->setMessage('您还未签到,请先签到');
                return $messageBag;
            }
            $status = MeetingUser::SIGN_OUT;
            // 当前时间小于最早签退时间 早退
            if($now < $meet->meeting->signout_start) {
                $status = MeetingUser::SIGN_OUT_EARLY;
            }
            $data = ['my_signout_status'=>$status, 'end'=>$now];

        }
        $re = MeetingUser::where($map)->update($data);
        if($re) {
            $messageBag->setMessage($msg.'成功');
        } else {
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
            $messageBag->setMessage($msg.'失败');
        }
        return $messageBag;
    }
}
