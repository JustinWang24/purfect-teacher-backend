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
use App\Dao\Affiche\Api\MessageDao;; // 评论
use App\Dao\Affiche\Api\UserFollowDao; // 关注

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
}
