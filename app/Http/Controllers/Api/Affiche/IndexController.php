<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/31
 * Time: 11:33 AM
 */
namespace App\Http\Controllers\Api\Affiche;

use App\Dao\Affiche\Api\PraiseDao;
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
           $school_id = $user->gradeUser->school_id;
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
               $videoInfo = $afficheobj->getAfficheVideoOneInfo($val['icheid']);
               // 合并数据
               $val = array_merge($val, ['picsList' => $picsList,'videoInfo' => $videoInfo]);
           }
       }

      return JsonBuilder::Success ( $infos , '首页推荐列表' );
   }

}
