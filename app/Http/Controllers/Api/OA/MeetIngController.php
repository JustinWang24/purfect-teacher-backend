<?php


namespace App\Http\Controllers\Api\OA;


use App\Dao\OA\GroupMemberDao;
use App\Dao\OA\MeetingDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\OA\MeetingRequest;
use App\Models\OA\GroupMember;
use App\Utils\JsonBuilder;

class MeetIngController extends Controller
{
    /**
     * 创建会议
     * @param MeetingRequest $request
     * @return string
     */
    public function addMeeting(MeetingRequest $request) {
        $data = $request->all();
        $schoolId = $request->user()->getSchoolId();
        $data['school_id'] = $schoolId;

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
