<?php


namespace App\Http\Controllers\Api\OA;


use App\Dao\OA\MeetingDao;
use App\Utils\JsonBuilder;
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

}
