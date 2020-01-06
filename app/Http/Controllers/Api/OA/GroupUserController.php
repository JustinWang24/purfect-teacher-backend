<?php


namespace App\Http\Controllers\Api\OA;


use App\Utils\JsonBuilder;
use App\Dao\OA\GroupMemberDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\OA\GroupRequest;

class GroupUserController extends Controller
{

    /**
     * 添加成员
     * @param GroupRequest $request
     * @return string
     */
    public function addMember(GroupRequest $request) {
        $groupId = $request->getGroupId();
        $userIds = $request->get('userids');

        $schoolId = $request->user()->getSchoolId();
        $dao = new GroupMemberDao();
        $result = $dao->create($groupId, $schoolId, $userIds);
        if($result->isSuccess()) {
            return JsonBuilder::Success('添加成功');
        } else {
            return JsonBuilder::Error($result->getMessage());
        }
    }
}
