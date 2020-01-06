<?php


namespace App\Http\Controllers\Api\OA;


use App\Dao\OA\GroupDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\OA\GroupRequest;
use App\Utils\JsonBuilder;

class GroupController extends Controller
{
    /**
     * 创建分组
     * @param GroupRequest $request
     * @return string
     */
    public function addGroup(GroupRequest $request) {
        $name = $request->getGroupName();
        $user = $request->user();
        $dao = new GroupDao();
        $result = $dao->create($name, $user->id, $user->getSchoolId());
        $msg = $result->getMessage();
        if($result->isSuccess()) {
            return JsonBuilder::Success($result->getData(),$msg);
        } else {
            return JsonBuilder::Error($msg);
        }
    }


    /**
     * 分组列表
     * @param GroupRequest $request
     * @return string
     */
    public function groupList(GroupRequest $request) {
        $schoolId = $request->user()->getSchoolId();
        $dao = new GroupDao();
        $list = $dao->groupList($schoolId);

        $data = [];
        foreach ($list as $key => $val) {
            $data[$key]['groupid'] = $val->id;
            $data[$key]['name']    = $val->name;
            $member = $val->groupMember;
            $data[$key]['count']   = $member->count();
            $data[$key]['list'] = [];
            foreach ($member as $k => $v) {
                $data[$key]['list'][$k]['userid'] = $v->user_id;
                $data[$key]['list'][$k]['user_username'] = $v->user->name;
                $data[$key]['list'][$k]['user_pics'] = $v->user->profile->avatar ?? '';
            }
        }

        return JsonBuilder::Success($data);

    }
}
