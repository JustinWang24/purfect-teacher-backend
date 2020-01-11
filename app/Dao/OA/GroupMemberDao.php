<?php


namespace App\Dao\OA;


use App\Utils\JsonBuilder;
use App\Models\OA\GroupMember;
use Illuminate\Support\Facades\DB;
use App\Utils\ReturnData\MessageBag;

class GroupMemberDao
{

    /**
     * 添加成员
     * @param $groupId
     * @param $schoolId
     * @param $userIds
     * @return MessageBag
     */
    public function create($groupId, $schoolId ,$userIds) {
        $userIdArr = explode(',',$userIds);
        $data = [];
        $messageBag = new MessageBag();
        try {
            DB::beginTransaction();
            foreach ($userIdArr as $key => $item) {
                // 判断该成员是否已存在
                $re = $this->getGroupMemberByUserIdAndGroup($item,$groupId);
                if(is_null($re)) {
                    $data['group_id']  = $groupId;
                    $data['school_id'] = $schoolId;
                    $data['user_id'] = $item;
                    GroupMember::create($data);
                }

            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $messageBag->setMessage($e->getMessage());
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
        }
        return $messageBag;
    }

    /**
     * @param $groupIds
     * @return mixed
     */
    public function getGroupMemberByGroupIds($groupIds) {
        return GroupMember::whereIn('group_id',$groupIds)->get();
    }


    /**
     * 删除成员
     * @param $groupId
     * @param $userId
     * @return mixed
     */
    public function deleteMember($groupId, $userId) {
        $map = ['group_id'=>$groupId, 'user_id'=>$userId];
        return GroupMember::where($map)->delete();
    }


    /**
     * @param $userId
     * @param $groupId
     * @return mixed
     */
    public function getGroupMemberByUserIdAndGroup($userId, $groupId) {
        $map = ['user_id'=>$userId, 'group_id'=>$groupId];
        return GroupMember::where($map)->first();
    }
}
