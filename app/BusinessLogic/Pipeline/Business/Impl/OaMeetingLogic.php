<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 4/12/19
 * Time: 8:49 PM
 */

namespace App\BusinessLogic\Pipeline\Business\Impl;

use App\Dao\OA\NewMeetingDao;
use App\Events\SystemNotification\OaMeetingEvent;
use App\User;
use App\Utils\JsonBuilder;
use App\Models\OA\NewMeeting;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\Log;

class OaMeetingLogic
{
    public $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle($options)
    {
        Log::debug($options);
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        try {
            // 审核会议
            $map = ['id'=>$options['meet_id']];
            if($options['pipeline_done'] == 1) {
                $status = NewMeeting::STATUS_PASS; // 通过
            } else {
                $status = NewMeeting::STATUS_REFUSE; // 拒绝
            }
            $save = ['status'=>$status];
            NewMeeting::where($map)->update($save);


//            if($status == NewMeeting::STATUS_PASS) {
//                $dao = new NewMeetingDao();
//                $meet = $dao->meetDetails($options['meet_id']);
//                // 参会人员
//                $users = $meet->meetUsers->pluck('user_id')->toArray();
//                array_push($users, $meet['approve_userid']);
//                $users = array_unique($users);
//                //通知负责人和成员
//                foreach ($users as $userid) {
//                    event(new OaMeetingEvent($userid, $meet->id));
//                }
//            }


            $bag->setCode(JsonBuilder::CODE_SUCCESS);
        }catch (\Exception $exception) {
            $bag->setMessage( $exception->getMessage());
        }
        return $bag;
    }

}
