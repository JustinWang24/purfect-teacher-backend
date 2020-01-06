<?php


namespace App\Dao\OA;


use App\Models\OA\Group;
use App\Utils\JsonBuilder;
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
//            ->select($field)
            ->get();
    }
}
