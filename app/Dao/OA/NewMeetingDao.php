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
use App\Utils\ReturnData\MessageBag;
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



}