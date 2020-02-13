<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/2/10
 * Time: 下午1:05
 */

namespace App\Dao\OA;


use App\Models\OA\NewMeeting;
use App\Models\OA\NewMeetingFile;
use App\Models\OA\NewMeetingSummary;
use App\Models\OA\NewMeetingUser;
use App\Utils\JsonBuilder;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Psy\Util\Json;
use Ramsey\Uuid\Uuid;

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
     * @param $user
     * @param $file
     * @return MessageBag
     */
    public function addMeeting($data, $user, $file) {
        $messageBag = new MessageBag();
        try {
            DB::beginTransaction();
            $meeting = NewMeeting::create($data);

            foreach ($user as $key => $item) {
                $meetUser = [
                    'meet_id' => $meeting->id,
                    'user_id' => $item,
                ];
                NewMeetingUser::create($meetUser);
            }

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
     * @param $userId
     * @return mixed
     */
    public function unfinishedMeet($userId) {
        $now = Carbon::now()->toDateTimeString();
        $map = [
            ['new_meeting_users.user_id','=', $userId],
            ['new_meetings.meet_end', '>', $now],
        ];

        $field = ['new_meeting_users.*', 'new_meeting_users.signin_status as status', 'new_meetings.*'];

        $list = NewMeetingUser::join('new_meetings', function ($join) use ($map){
            $join->on('new_meetings.id', '=', 'new_meeting_users.meet_id')
                ->where($map)
                ->orderBy('new_meetings.meet_start');
        })->select($field)->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
        return $list;
    }


    /**
     * 已完成会议
     * @param $userId
     * @return mixed
     */
    public function accomplishMeet($userId) {
        $now = Carbon::now()->toDateTimeString();
        $map = [
            ['new_meeting_users.user_id','=', $userId],
            ['new_meetings.meet_end', '<=', $now],
        ];

        $field = ['new_meeting_users.*', 'new_meeting_users.signin_status as signIn_status',
            'new_meeting_users.signout_status as signOut_status', 'new_meetings.*'];
        $list = NewMeetingUser::join('new_meetings', function ($join) use ($map){
            $join->on('new_meetings.id', '=', 'new_meeting_users.meet_id')
                ->where($map)
                ->orderBy('new_meetings.meet_start');
        })->select($field)->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
        return $list;
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




}