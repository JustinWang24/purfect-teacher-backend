<?php


namespace App\Dao\OA;


use App\Models\OA\Meeting;
use App\Models\OA\MeetingUser;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

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
        return Meeting::where($map)->get();
    }
}
