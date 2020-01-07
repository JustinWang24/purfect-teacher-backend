<?php


namespace App\Http\Controllers\Api\OA;


use App\Dao\OA\GroupDao;
use App\Dao\OA\GroupMemberDao;
use App\Utils\JsonBuilder;
use App\Dao\Users\UserDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\OA\GroupRequest;

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


    /**
     * 教师列表
     * @param GroupRequest $request
     * @return string
     */
    public function userList(GroupRequest $request) {
        $keyword = $request->getKeyWord();
        $schoolId = $request->user()->getSchoolId();
        $userDao = new UserDao();
        $list = $userDao->getTeachersBySchool($schoolId, false, $keyword);
        $data = [];
        foreach ($list as $key => $val) {
            $data[$key]['userid'] = $val->id;
            $data[$key]['user_username'] = $val->name;
            $data[$key]['user_pics'] = $val->user->profile->avatar ?? '';
            $data[$key]['duties'] = $val->user->profile->category_teach ?? '';
        }
        return JsonBuilder::Success($data);
    }


    /**
     * 删除分组
     * @param GroupRequest $request
     * @return string
     */
    public function delGroup(GroupRequest $request) {
        $groupId = $request->getGroupId();
        $groupDao = new GroupDao();
        $result = $groupDao->deleteGroup($groupId);
        $msg = $result->getMessage();
        if($result->isSuccess()) {
            return JsonBuilder::Success($msg);
        } else {
            return JsonBuilder::Error($msg);
        }
    }


    /**
     * 删除成功
     * @param GroupRequest $request
     * @return string
     */
    public function delMember(GroupRequest $request) {
        $groupId = $request->getGroupId();
        $userId = $request->get('userid');
        $dao = new GroupMemberDao();
        $result = $dao->deleteMember($groupId, $userId);
        if($result) {
            return JsonBuilder::Success('删除成功');
        } else {
            return JsonBuilder::Error('删除失败');
        }
    }
}
