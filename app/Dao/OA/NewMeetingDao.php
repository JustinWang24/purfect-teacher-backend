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
use App\Models\OA\NewMeetingUser;
use App\Utils\JsonBuilder;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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



}