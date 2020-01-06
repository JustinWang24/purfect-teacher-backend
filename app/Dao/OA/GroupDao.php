<?php


namespace App\Dao\OA;


use App\Models\OA\Group;
use App\Utils\JsonBuilder;
use App\Models\OA\GroupMember;
use Illuminate\Support\Facades\DB;
use App\Utils\ReturnData\MessageBag;

class GroupDao
{
    /**
     * 创建组
     * @param $name
     * @param $userId
     * @param $schoolId
     * @return MessageBag
     */
    public function create($name, $userId, $schoolId) {
        $messageBag = new MessageBag();
        $re = $this->getGroupByName($name, $schoolId);
        if(!is_null($re)) {
            $messageBag->setCode(JsonBuilder::Error());
            $messageBag->setMessage('该组已存在');
            return $messageBag;
        }
        $data = ['name'=>$name, 'user_id'=>$userId, 'school_id'=>$schoolId];
        $result = Group::create($data);
        if($result) {
            $messageBag->setData(['groupid'=>$result->id]);
            $messageBag->setMessage('创建成功');
        } else {
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
            $messageBag->setMessage('创建失败');
        }
        return $messageBag;
    }

    /**
     * 查询组
     * @param $name
     * @param $schoolId
     * @return mixed
     */
    public function getGroupByName($name, $schoolId) {
        $map = ['name'=>$name, 'school_id'=>$schoolId];
        return Group::where($map)->first();
    }


    /**
     * 组列表
     * @param $schoolId
     * @return mixed
     */
    public function groupList($schoolId) {
        $map = ['school_id'=>$schoolId];
        $field = ['id', 'name'];
        return Group::where($map)
            ->select($field)
            ->get();
    }


    /**
     * 删除分组
     * @param $groupId
     * @return MessageBag
     */
    public function deleteGroup($groupId) {
        $messageBag = new MessageBag();
        try{
            DB::beginTransaction();
            Group::where(['id'=>$groupId])->delete();
            GroupMember::where(['group_id'=>$groupId])->delete();
            DB::commit();
            $messageBag->setMessage('删除成功');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
            $messageBag->setMessage($msg);
        }
        return $messageBag;
    }
}
