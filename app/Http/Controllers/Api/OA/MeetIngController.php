<?php


namespace App\Http\Controllers\Api\OA;


use App\Dao\OA\MeetingDao;
use App\Models\OA\Meeting;
use App\Models\OA\MeetingUser;
use App\Utils\JsonBuilder;
use App\Dao\OA\GroupMemberDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\OA\MeetingRequest;
use Carbon\Carbon;

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
        foreach ($list as $key => $item) {
            $data[$key]['meetid'] = $item->id;
            $data[$key]['meet_title'] = $item->meet_title;
            $data[$key]['meet_address'] = $item->meet_address;
            $data[$key]['user_username'] = $item->user->name;
            $signinStart = Carbon::parse($item->signin_start);
            $signinEnd = Carbon::parse($item->signin_end)->format('H:i');
            $signin = $signinStart->format('Y-m-d').' '.$signinStart->format('H:i').'-'.$signinEnd;
            $meetStart = Carbon::parse($item->meet_start);
            $meetEnd = Carbon::parse($item->meet_end)->format('H:i');
            $meet = $meetStart->format('Y-m-d').' '.$meetStart->format('H:i').'-'.$meetEnd;
            $data[$key]['signin_transtime'] = $signin;
            $data[$key]['meet_transtime'] = $meet;
            $data[$key]['button_status'] = 'signin';
            $data[$key]['button_tip'] = 'normal';
            $now = Carbon::now()->toDateTimeString();
            if($item->status == MeetingUser::UN_SIGN_IN) {
                $data[$key]['button_status'] = 'signin';
                if($now > $item->meet_start) {
                    $data[$key]['button_tip'] = 'late'; // 迟到
                }
            } elseif ($item->status == MeetingUser::SIGN_IN) {
                $data[$key]['button_status'] = 'signout';
                if($item->signout_status == Meeting::SIGN_OUT && $now < $item->signout_start ) {
                    $data[$key]['button_tip'] = 'early'; // 早退

                }
            }
        }

        return JsonBuilder::Success($data);
    }

}
