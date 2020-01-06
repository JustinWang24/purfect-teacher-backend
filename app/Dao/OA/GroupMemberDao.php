<?php


namespace App\Dao\OA;


use App\Utils\JsonBuilder;
use App\Models\OA\GroupMember;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

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
                $data['group_id']  = $groupId;
                $data['school_id'] = $schoolId;
                $data['user_id'] = $item;
                GroupMember::create($data);
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $messageBag->setMessage($e->getMessage());
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
        }
        return $messageBag;
    }
}
