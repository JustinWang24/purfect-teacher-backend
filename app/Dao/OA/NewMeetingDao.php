<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/2/10
 * Time: 下午1:05
 */

namespace App\Dao\OA;


use App\User;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Utils\JsonBuilder;
use App\Models\OA\NewMeeting;
use App\Dao\Pipeline\ActionDao;
use App\Models\OA\NewMeetingFile;
use App\Models\OA\NewMeetingUser;
use Illuminate\Support\Facades\DB;
use App\Models\OA\NewMeetingSummary;
use App\Utils\ReturnData\MessageBag;
use App\Utils\Misc\ConfigurationTool;

class NewMeetingDao
{

    /**
     * 根据时间获取会议
     * @param $schoolId
     * @param $date
     * @return mixed
     */
    public function getMeetingByDate($schoolId, $date){
        return NewMeeting::where('school_id', $schoolId)
            ->where('type', NewMeeting::TYPE_MEETING_ROOM)
            ->whereDate('meet_start', $date)
            ->get();
    }


    /**
     * 创建会议
     * @param $data
     * @param $users
     * @param $file
     * @param User $createUser
     * @return MessageBag
     */
    public function addMeeting($data, $users, $file, User $createUser) {

        $messageBag = new MessageBag();
        try {
            $room = $data['room'];
            unset($data['room']);
            if($data['type'] == NewMeeting::TYPE_MEETING_ROOM) {
                $data['room_id'] = $room;
            } else {
                // 自定义会议 直接通过
                $data['room_text'] = $room;
                $data['status'] = NewMeeting::STATUS_PASS;
            }
            DB::beginTransaction();
            $meeting = NewMeeting::create($data);

            // 判断是否需要审批
            if($data['type'] == NewMeeting::TYPE_MEETING_ROOM) {
                $actionDao = new ActionDao();
                $re = $actionDao->createMeetingFlow($createUser,$meeting->id);
                if(!$re->isSuccess()) {
                    throw new \Exception($re->getMessage());
                }
            }

            foreach ($users as $key => $item) {
                $meetUser = [
                    'meet_id' => $meeting->id,
                    'user_id' => $item,
                ];
                NewMeetingUser::create($meetUser);
            }

            if(!is_null($file)) {
                $path = NewMeetingFile::DEFAULT_UPLOAD_PATH_PREFIX.$data['user_id']; // 上传路径
                foreach ($file as  $key => $value) {
                    $uuid = Uuid::uuid4()->toString();
                    $url = $value->storeAs($path, $uuid.'.'.$value->getClientOriginalExtension()); // 上传并返回路径
                    $meetFile = [
                        'meet_id' => $meeting->id,
                        'file_name'   => $value->getClientOriginalName(),
                        'url'         => NewMeetingFile::ConvertUploadPathToUrl($url),
                    ];
                    NewMeetingFile::create($meetFile);
                }
            }

            DB::commit();

            $messageBag->setData(['meet_id'=>$meeting->id]);

        }catch (\Exception $e) {

            DB::rollBack();
            $msg = $e->getMessage();
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
            $messageBag->setMessage('创建失败'.$msg);
        }
        return  $messageBag;
    }


    /**
     * 待完成
     * @param $userId
     * @return mixed
     */
    public function unfinishedMeet($userId) {
        $now = Carbon::now()->toDateTimeString();
        $meetUser = NewMeetingUser::where('user_id',$userId)->get()->toArray();
        if(count($meetUser) == 0) {
            return null;
        }
        $meetIds = array_column($meetUser, 'meet_id');
        // 不需要签退
        $map = [
            ['new_meetings.meet_end', '>=', $now],
            ['new_meetings.status', '=', NewMeeting::STATUS_PASS],
            ['new_meetings.signout_status', '=', NewMeeting::NOT_SIGNOUT],
        ];
        // 需要签退
        $where = [
            ['new_meetings.signout_end', '>=', $now],
            ['new_meetings.status', '=', NewMeeting::STATUS_PASS],
            ['new_meetings.signout_status', '=', NewMeeting::SIGNOUT],
        ];


        return NewMeeting::where($map)
            ->orWhere($where)
            ->whereIn('id', $meetIds)
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }


    /**
     * 已完成会议
     * @param User $user
     * @return mixed
     */
    public function accomplishMeet($user) {
        $now = Carbon::now()->toDateTimeString();
        $meetUser = NewMeetingUser::where('user_id',$user->id)->get()->toArray();
        if(count($meetUser) == 0) {
            return null;
        }
        $schoolId = $user->getSchoolId();
        $meetIds = array_column($meetUser, 'meet_id');

        // 不需要签退
        $map = [
            ['meet_end', '<', $now],
            ['status', '=', NewMeeting::STATUS_PASS],
            ['signout_status', '=', NewMeeting::NOT_SIGNOUT],
            ['school_id', '=', $schoolId]
        ];
        // 需要签退
        $where = [
            ['signout_end', '<', $now],
            ['status', '=', NewMeeting::STATUS_PASS],
            ['signout_status', '=', NewMeeting::SIGNOUT],
            ['school_id', '=', $schoolId],
        ];

        return NewMeeting::where($map)
            ->orwhere($where)
            ->whereIn('id',$meetIds)
            ->orderBy('meet_start','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }


    /**
     * 自己创建的
     * @param $userId
     * @return mixed
     */
    public function oneselfCreateMeet($userId) {
        $map = ['user_id'=>$userId];
        $list = NewMeeting::where($map)
            ->orderBy('meet_start','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
        return $list;
    }


    /**
     * 会议详情
     * @param $meetId
     * @return mixed
     */
    public function meetDetails($meetId) {
        return NewMeeting::where('id', $meetId)->first();
    }


    /**
     * 保存会议纪要
     * @param $meetId
     * @param $userId
     * @param $summaries
     * @return MessageBag
     */
    public function saveMeetSummary($meetId,$userId,$summaries) {
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        $map = ['meet_id'=>$meetId, 'user_id'=>$userId];
        $meetUser = NewMeetingUser::where($map)->first();
        if(is_null($meetUser)) {
            $messageBag->setMessage('您不是该会议的成员');
            return $messageBag;
        }

        try {
            DB::beginTransaction();
            foreach ($summaries as $item) {
                $path = NewMeetingSummary::DEFAULT_UPLOAD_PATH_PREFIX.$userId; // 上传路径
                $uuid = Uuid::uuid4()->toString();
                $url = $item->storeAs($path, $uuid.'.'.$item->getClientOriginalExtension()); // 上传并返回路径
                $summary = [
                    'meet_id' => $meetId,
                    'meet_user_id' => $meetUser->id,
                    'user_id' => $userId,
                    'url'     => NewMeetingSummary::ConvertUploadPathToUrl($url),
                    'file_name' => $item->getClientOriginalName(),
                ];

                NewMeetingSummary::create($summary);

            }

            DB::commit();
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            $messageBag->setMessage('上传成功');

        } catch (\Exception $e) {

            DB::rollBack();
            $msg = $e->getMessage();
            $messageBag->setMessage($msg);
        }

        return $messageBag;
    }


    /**
     * 获取会议纪要
     * @param $meetId
     * @param $userId
     * @param bool $simple 简单数据
     * @return mixed
     */
    public function getMeetSummary($meetId, $userId,$simple = true) {
        $map = ['meet_id'=>$meetId, 'user_id'=>$userId];
        $field = '*';
        if($simple) {
            $field = ['id', 'url', 'file_name'];
        }
        return NewMeetingSummary::where($map)->select($field)->get();
    }


    /**
     * 获取签到详情
     * @param $meetId
     * @param $userId
     * @return mixed
     */
    public function getMeetUser($meetId, $userId) {
        $map = ['meet_id'=>$meetId, 'user_id'=>$userId];
        return NewMeetingUser::where($map)->first();
    }


    /**
     * @param $meetId
     * @return mixed
     */
    public function getMeetByMeetId($meetId) {
        return NewMeeting::where('id', $meetId)->first();
    }


    /**
     * 获取会议参会人员-分页
     * @param $meetId
     * @return mixed
     */
    public function getMeetUserPageByMeetId($meetId) {
        return NewMeetingUser::where('meet_id',$meetId)
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }


    /**
     * 签到或签退
     * @param NewMeeting $meet
     * @param $meetUserId
     * @param $type
     * @return MessageBag
     */
    public function saveSignIn(NewMeeting $meet, $meetUserId, $type) {
        $messageBag = new MessageBag();
        $now = Carbon::now()->toDateTimeString();
        $map = ['id'=>$meetUserId];
        $meetingUser = NewMeetingUser::where($map)->first();
        if($type == 'signIn') {
            if($meetingUser->signin_status !== NewMeetingUser::UN_SIGNIN) {
                $messageBag->setMessage('已签到');
                return $messageBag;
            }
            // 签到时间大于会议开始时间
            if($now > $meet->meet_start) {
                $status = 2; // 迟到
            } else {
                $status = 1; // 正常
            }
            $msg = '签到';
            $save = ['signin_status'=>$status, 'signin_time'=>$now];
        } else {

            if($meetingUser->signout_status !== NewMeetingUser::UN_SIGNOUT) {
                $messageBag->setMessage('已签退');
                return $messageBag;
            }

            if($now < $meet->meet_end ) {
                $status = 2; // 早退
            } else {
                $status = 1; // 正常
            }
            $msg = '签退';
            $save = ['signout_status'=>$status, 'signout_time'=>$now];
        }

        $result = NewMeetingUser::where($map)->update($save);
        if($result) {
            $messageBag->setMessage($msg.'成功');
        } else {
            $messageBag->setMessage($msg.'失败');
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
        }
        return $messageBag;
    }


    /**
     * 获取学校会议列表
     * @param $schoolId
     * @return mixed
     */
    public function getMeetingBySchoolId($schoolId) {
        return NewMeeting::where('school_id',$schoolId)
            ->orderBy('created_at','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }


}