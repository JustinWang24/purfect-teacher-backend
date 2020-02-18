<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/31
 * Time: 11:33 AM
 */
namespace App\Http\Controllers\Api\Affiche;

use App\Dao\Affiche\CommonDao;
use Psy\Util\Json;
use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Affiche\FollowRequest;

use App\Dao\Users\UserDao; // 用户
use App\Dao\Affiche\Api\AfficheDao; // 动态
use App\Dao\Affiche\Api\MessageDao; // 评论
use App\Dao\Affiche\Api\UserFollowDao; // 关注
use App\Models\Affiche\UserFollow; // 用户关注模型

use Illuminate\Support\Facades\DB;
use App\BusinessLogic\WifiInterface\Factory;

class FollowController extends Controller
{
    /**
     * Func 用户关注和取消关注接口
     *
     * @param Request $request
     * @param['token'] 是   token
     * @param['touser_id'] 是  用户id
     *
     * @return Json
     */
    public function follow_edit_info(FollowRequest $request)
    {
        $token = (String)$request->input('token', '');
        $touser_id = (Int)$request->input('touser_id', 0);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!intval($touser_id)) {
            return JsonBuilder::Error('参数错误');
        }

        $user = $request->user();
        $user_id = $user->id;

        $userFollowobj = new UserFollowDao();
        $infos = $userFollowobj->addOrUpdateUserFollowInfo($user_id,$touser_id);

        return JsonBuilder::Success($infos, '操作成功');
    }

    /**
     * Func 已关注列表接口
     *
     * @param Request $request
     * @param['token'] 是   token
     * @param['page'] 是  分页id
     *
     * @return Json
     */
    public function follow_adopt_list_info(FollowRequest $request)
    {
        $token = (String)$request->input('token', '');
        $page = (Int)$request->input('page', 1);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }

        $user = $request->user();
        $user_id = $user->id;

        $userFollowobj = new UserFollowDao();
        $infos = $userFollowobj->getUserFollowListInfo($user_id, $page);
        if (!empty($infos)) {
            foreach ($infos as $key => &$val) {
                $userInfo = $userFollowobj->userInfo($val['touser_id']);
                $val['user_id'] = $userInfo['user_id'];
                $val['user_nickname'] = $userInfo['user_nickname'];
                $val['user_pics'] = $userInfo['user_pics'];
                $val['user_signture'] = $userInfo['user_signture'];
                $val['school_name'] = $userInfo['school_name'];
                unset($val['touser_id']);
            }
        }
        return JsonBuilder::Success($infos, '已关注用户列表');
    }

    /**
     * Func 未关注列表
     *
     * @param Request $request
     * @param['token'] 是   token
     * @param['page'] 是  分页id
     *
     * @return Json
     */
    public function follow_refer_list_info(FollowRequest $request)
    {
        $token = (String)$request->input('token', '');
        $page = (Int)$request->input('page', 1);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }

        $user = $request->user();
        $user_id = $user->id;
        $school_id = $user->gradeUser->school_id;

        $userIdArr = [];
        $userFollowobj = new UserFollowDao();
        $data = $userFollowobj->getUserFollowListInfo($user_id, 1, 1000);
        if (!empty($data)) {
            $userIdArr = array_filter(array_unique(array_column($data, 'touser_id')));
        }

        $infos = [];
        $fieldArr = ['a.user_id'];
        if (!empty($userIdArr)) {
            $infos = UserFollow::from('grade_users as a')
                ->where('a.school_id', '=', $school_id)
                ->whereNotIn('a.user_id', $userIdArr)
                ->orderBy('a.id', 'desc')
                ->offset($userFollowobj->offset($page))
                ->limit(UserFollowDao::$limit)
                ->select($fieldArr)
                ->get();
            $infos = !empty($infos->toArray()) ? $infos->toArray() : [];
        } else {
            $infos = UserFollow::from('grade_users as a')
                ->where('a.school_id', '=', $school_id)
                ->orderBy('a.id', 'desc')
                ->offset($userFollowobj->offset($page))
                ->limit(UserFollowDao::$limit)
                ->select($fieldArr)
                ->get();
            $infos = !empty($infos->toArray()) ? $infos->toArray() : [];
        }
        if (!empty($infos)) {
            foreach ($infos as $key => &$val) {
                $userInfo = $userFollowobj->userInfo($val['user_id']);
                $val['user_id'] = $userInfo['user_id'];
                $val['user_nickname'] = $userInfo['user_nickname'];
                $val['user_pics'] = $userInfo['user_pics'];
                $val['user_signture'] = $userInfo['user_signture'];
                $val['school_name'] = $userInfo['school_name'];
                unset($val['touser_id']);
            }
        }

        return JsonBuilder::Success($infos, '未关注用户列表');

    }

    /**
     * Func 搜索用户列表
     *
     * @param Request $request
     * @param['token'] 是   token
     * @param['page'] 是  分页id
     *
     * @return Json
     */
    public function follow_search_list_info(FollowRequest $request)
    {
        $token = (String)$request->input('token', '');
        $typeid = (Int)$request->input('typeid', '');
        $q = (String)$request->input('q', '');
        $page = (Int)$request->input('page', 1);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!in_array($typeid, [1, 2])) {
            return JsonBuilder::Error('类型错误');
        }
        if (trim($q) == '') {
            return JsonBuilder::Error('请输入关键词');
        }

        $user = $request->user();
        $user_id = $user->id;
        $school_id = $user->gradeUser->school_id;

        $userFollowobj = new UserFollowDao();

        // 已关注
        $infos = UserFollow::from('user_follows as a')
            ->join('users as b', 'a.touser_id', '=', 'b.id')
            ->where('a.user_id', '=', $user_id)
            ->where('b.name', 'like','%' . trim($q) . '%')
            ->orderBy('a.llowid', 'desc')
            ->offset($userFollowobj->offset($page))
            ->limit(UserFollowDao::$limit)
            ->select(['a.touser_id as user_id'])
            ->get();
        $infos = !empty($infos->toArray()) ? $infos->toArray() : [];

        $userIdArr = [];
        if (!empty($infos)) {
            $userIdArr = array_filter(array_unique(array_column($infos, 'user_id')));
        }

        if ($typeid == 2) {
            if (!empty($userIdArr)) {
                $infos = UserFollow::from('grade_users as a')
                    ->join('users as b', 'a.user_id', '=', 'b.id')
                    ->where('a.school_id', '=', $school_id)
                    ->where('b.name', 'like','%' . trim($q) . '%')
                    ->whereNotIn('a.user_id', $userIdArr)
                    ->orderBy('a.id', 'desc')
                    ->offset($userFollowobj->offset($page))
                    ->limit(UserFollowDao::$limit)
                    ->select(['a.user_id'])
                    ->get();
                $infos = !empty($infos->toArray()) ? $infos->toArray() : [];
            } else {
                $infos = UserFollow::from('grade_users as a')
                    ->join('users as b', 'a.user_id', '=', 'b.id')
                    ->where('a.school_id', '=', $school_id)
                    ->where('b.name', 'like', '%' . trim($q) . '%')
                    ->orderBy('a.id', 'desc')
                    ->offset($userFollowobj->offset($page))
                    ->limit(UserFollowDao::$limit)
                    ->select(['a.user_id'])
                    ->get();
                $infos = !empty($infos->toArray()) ? $infos->toArray() : [];
            }
        }

        if (!empty($infos)) {
            foreach ($infos as $key => &$val) {
                $userInfo = $userFollowobj->userInfo($val['user_id']);
                $val['user_id'] = $userInfo['user_id'];
                $val['user_nickname'] = $userInfo['user_nickname'];
                $val['user_pics'] = $userInfo['user_pics'];
                $val['user_signture'] = $userInfo['user_signture'];
                $val['school_name'] = $userInfo['school_name'];
                unset($val['touser_id']);
            }
        }

        return JsonBuilder::Success($infos, '搜索用户列表');

    }
}
