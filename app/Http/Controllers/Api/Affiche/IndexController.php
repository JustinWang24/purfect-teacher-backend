<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/31
 * Time: 11:33 AM
 */
namespace App\Http\Controllers\Api\Affiche;

use App\Dao\Affiche\Api\PraiseDao;
use App\Dao\Affiche\Api\UserFollowDao;
use Psy\Util\Json;
use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Affiche\IndexRequest;

use Illuminate\Support\Facades\DB;
use App\BusinessLogic\WifiInterface\Factory;

use App\Dao\Users\UserDao;
use App\Dao\Affiche\Api\IndexDao;
use App\Dao\Affiche\Api\AfficheDao;

/**
 * 主要用于社区首页的接口
 * @author  zk
 * @version 1.0
 */
class IndexController extends Controller
{
    /**
     * Func 首页基础信息
     *
     * @param Request $request
     * @param['token'] 是  token
     *
     * @return Json
     */
    public function base_info(IndexRequest $request)
    {
        $infos = array(
            array('tag'=>'A','title'=>'学生会','pics'=>'/assets/img/affiche/校园互助@3x.png'),
            array('tag'=>'B','title'=>'社团','pics'=>'/assets/img/affiche/社团@3x.png'),
            array('tag'=>'C','title'=>'活动','pics'=>'/assets/img/affiche/活动@3x.png'),
        );
        return JsonBuilder::Success ( $infos , '首页基础信息' );
    }

    /**
     * Func 首页推荐列表
     *
     * @param Request $request
     * @param['token'] 是  token
     *
     * @return Json
     */
    public function index_info(IndexRequest $request)
    {
        $token = (String)$request->input('token', '');
        $page = (Int)$request->input('page', 1);

        // 如果传递token获取用户信息
        $user_id = 0;
        $school_id = 0;
        if ($token != '')
        {
            $user = $request->user();
            $user_id = $user->id;
            $school_id = $user->gradeUserOneInfo->school_id;
        }

        // 返回数据
        $infos = [];

        $indexobj = new IndexDao();
        $afficheobj = new AfficheDao();

        $infos = $indexobj->getStickListInfo($school_id, 1 , $page);

        if (!empty($infos) && is_array($infos))
        {
            $praiseobj = new PraiseDao();

            foreach ($infos as $key => &$val)
            {
                // 格式化时间
                $val['create_timestr'] = $indexobj->transTime1(strtotime($val['created_at']));

                // 是否点赞
                $val['ispraise'] = 0;
                if ($user_id) {
                    $val['ispraise'] = (Int)$praiseobj->getAffichePraiseCount(1, $user_id, $val['icheid']);
                }

                // 用户信息
                $userInfo = $afficheobj->userInfo($val['user_id']);
                $val['user_pics'] = (String)$userInfo['user_pics'];
                $val['user_nickname'] = (String)$userInfo['user_nickname'];
                $val['school_name'] = (String)$userInfo['school_name'];

                // 图片信息
                $picsList = $afficheobj->getAffichePicsListInfo($val['icheid']);
                // 视频信息
                $videoInfo = $afficheobj->getAfficheVideoOneInfo1($val['icheid']);
                // 合并数据
                $val = array_merge($val, ['picsList' => $picsList,'videoInfo' => $videoInfo]);
            }
        }

        return JsonBuilder::Success ( $infos , '首页推荐列表' );
    }

    /**
     * Func 社区个人主页
     *
     * @param Request $request
     * @param['token'] 是  token
     * @param['touser_id'] 是  用户id
     * @param['istrue'] 是  是否获取粉丝信息
     *
     * @return Json
     */
    public function user_index_info(IndexRequest $request)
    {
        $token = (String)$request->input('token', '');
        $touser_id = (Int)$request->input('touser_id', 0);
        $istrue = (Int)$request->input('istrue', 0);

        // 验证参数是否正确
        if (!intval($touser_id))
        {
            return JsonBuilder::Error('参数错误');
        }

        // 如果传递token获取用户信息
        $user_id = 0;
        if ($token != '')
        {
            $user = $request->user();
            $user_id = $user->id;
        }

        $indexobj = new IndexDao();

        // 用户信息
        $userInfo = $indexobj->userInfo($touser_id);
        $userPraiseInfo = $indexobj->getFansOrFocusOrPraiseNumber($touser_id);
        $userColorInfo = $indexobj->getUserColorInfo($touser_id);
        $infos['user_id'] = (int)$userInfo['user_id'];
        $infos['user_pics'] = (String)$userInfo['user_pics'];
        $infos['user_nickname'] = (String)$userInfo['user_nickname'];
        $infos['user_sex'] = (String)$userInfo['user_sex'];
        $infos['user_signture'] = (String)$userInfo['user_signture'];
        $infos['user_fans_number'] = (int)$userPraiseInfo['user_fans_number'];; // 粉丝数量
        $infos['user_focus_number'] = (int)$userPraiseInfo['user_focus_number'];; // 关注数量
        $infos['user_praise_number'] = (int)$userPraiseInfo['user_praise_number'];; // 点赞数量
        $infos['user_color_title'] = (String)$userColorInfo['user_color_title'];  // 名称
        $infos['user_color_small'] = (String)$userColorInfo['user_color_small'];  // 名称
        $infos['user_color_big'] = (String)$userColorInfo['user_color_big'];  // 名称
        $infos['school_name'] = (String)$userInfo['school_name'];

        // 是否关注
        $infos['isfollow'] = 0;
        if ($user_id) {
            $userFollowobj = new UserFollowDao();
            $infos['isfollow'] = (Int)$userFollowobj->getUserFollowCount($user_id, $infos['user_id']);
        }
        // 获取关注的人
        $infos['userFollowList'] = [];
        if ($istrue && $infos['user_id']) {
            $userFollowObj = new UserFollowDao();
            $infos['userFollowList'] = $userFollowObj->getUserFollowListInfo($infos['user_id'], 1);
            if (!empty($infos['userFollowList'])) {
                foreach ($infos['userFollowList'] as $key => &$val)
                {
                    $userInfo = $indexobj->userInfo($val['touser_id']);
                    $val['user_nickname'] = $userInfo['user_nickname'];
                    $val['user_pics'] = $userInfo['user_pics'];
                }
            }
        }
        return JsonBuilder::Success ( $infos , '社区个人主页' );
    }


}
