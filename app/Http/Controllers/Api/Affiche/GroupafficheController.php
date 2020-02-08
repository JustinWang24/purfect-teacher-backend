<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/31
 * Time: 11:33 AM
 */
namespace App\Http\Controllers\Api\Affiche;

use Psy\Util\Json;
use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Affiche\AfficheRequest;
use App\Http\Requests\Api\Affiche\GroupafficheRequest;

use App\Dao\Users\UserDao;
use App\Dao\Affiche\Api\AfficheDao;
use App\Dao\Affiche\Api\AffichePicsDao;
use App\Dao\Affiche\Api\AfficheVideoDao;
use App\Dao\Affiche\Api\PraiseDao;
use App\Dao\Affiche\Api\UserFollowDao;

use App\Models\Affiche\Affiche;
use App\Models\Notices\AppProposalImage;
use Illuminate\Support\Facades\DB;
use App\BusinessLogic\WifiInterface\Factory;

class GroupafficheController extends Controller
{
    /**
     * Func 群添加动态
     *
     * @param Request $request
     * @param['token']  是   token
     * @param['groupid']  是   群id
     * @param['iche_content']  是   内容
     * @param['iche_is_open_number']  是   是否公开(1:所有人,3:本校)
     * @param['iche_type']  是   动态类型(image:图片、文字,video:视频)
     * @param['iche_pic[]']  是   上传图片 iche_type=image时 使用
     * @param['iche_cover']  是   上传视频封面 iche_type=video时 使用
     * @param['iche_video']  是   上传视频 iche_type=video时 使用
     *
     * @return Json
     */
    public function add_groupaffiche_info(GroupafficheRequest $request)
    {
        $token = (String)$request->input('token', '');
        $groupid = (Int)$request->input('group_id', 0);
        $iche_content = (String)$request->input('iche_content', '');
        $iche_is_open_number = (Int)$request->input('iche_is_open_number', 1);
        $iche_type = (String)$request->input('iche_type', '');

        $iche_pic = (String)$request->input('iche_type', '');
        $iche_cover = (String)$request->input('iche_cover', '');
        $iche_video = (String)$request->input('iche_video', '');

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!intval($groupid)) {
            return JsonBuilder::Error('参数错误');
        }
        if (empty($iche_type) || !in_array($iche_type, ['image', 'video'])) {
            return JsonBuilder::Error('类型错误');
        }

        $user = $request->user();
        $user_id = $user->id;
        $school_id = $user->gradeUser->school_id;
        $campus_id = $user->gradeUser->campus_id;

        // 上传图片
        $iche_pic = [];
        $images = $request->file('iche_pic');
        if (!empty($images)) {
            foreach ($images as $key => $val) {
                $iche_pic[$key] = AppProposalImage::proposalUploadPathToUrl($val->store(AppProposalImage::DEFAULT_UPLOAD_PATH_PREFIX));
            }
        }

        // 上传视频封面
        $iche_cover = '';
        $coverObj = $request->file('iche_cover');
        if (!empty($coverObj)) {
            $iche_cover = AppProposalImage::proposalUploadPathToUrl(
                $coverObj->store(AppProposalImage::DEFAULT_UPLOAD_PATH_PREFIX)
            );
        }

        // 上传视频
        $iche_video = '';
        $vidwoObj = $request->file('iche_video');
        if (!empty($vidwoObj)) {
            $iche_video = AppProposalImage::proposalUploadPathToUrl(
                $vidwoObj->store(AppProposalImage::DEFAULT_UPLOAD_PATH_PREFIX)
            );
        }

        // 图片上传验证
        if ($iche_type == 'image') {
            // 如果内容和图片都为空，则提示。
            if (empty($iche_pic) && empty($iche_content)) {
                return JsonBuilder::Error('内容或图片不能全部为空');
            }
        }

        // 视频上传验证,视频上传需要封面图
        if ($iche_type == 'video') {
            // 视频封面图
            if (empty($iche_cover)) {
                return JsonBuilder::Error('请上传视频封面图');
            }
            // 视频文件
            if (empty($iche_video)) {
                return JsonBuilder::Error('请上传视频文件');
            }
        }

        // 添加数据
        $addData['status'] = 1;
        $addData['cate_id'] = 2; // 类型(1:普通动态,2:群组动态)
        $addData['is_source'] = 1; // 数据来源(1:app发布,2:后台发布)
        $addData['minx_id'] = (Int)$groupid;
        $addData['iche_type'] = (String)$iche_type;
        $addData['iche_content'] = trim($iche_content);
        $addData['user_id'] = $user_id;
        $addData['school_id'] = $school_id;
        $addData['campus_id'] = $campus_id;

        // 添加动态
        $afficheObj = new AfficheDao();
        if ($icheid = $afficheObj->addAffichesInfo($addData)) {

            // 图片上传
            if ($iche_type == 'image' && !empty($iche_pic)) {
                $affichePicsObj = new AffichePicsDao();
                foreach ($iche_pic as $key => $val) {
                    // 图片添加信息
                    $picsAddData['iche_id'] = $icheid;
                    $picsAddData['pics_bigurl'] = $val;
                    $picsAddData['pics_smallurl'] = $val;
                    $picsAddData['user_id'] = $user_id;
                    $affichePicsObj->addAffichesPicsInfo($picsAddData);
                }
            }

            // 视频上传
            if ($iche_type == 'video' && $iche_cover && $iche_video) {
                $afficheVideoObj = new AfficheVideoDao();
                // 视频添加信息
                $videoData['iche_id'] = $icheid;
                $videoData['user_id'] = $user_id;
                $videoData['video_url'] = $iche_cover; // 视频地址
                $videoData['cover_url'] = $iche_video; // 视频封面图
                $afficheVideoObj->addAfficheVideoInfo($videoData);
            }

            return JsonBuilder::Success('操作成功');

        } else {
            return JsonBuilder::Error('操作失败,请稍后重试');
        }
    }

    /**
     * Func 群组动态列表
     *
     * @param Request $request
     * @param['typeid'] 是   类型(1:自己的,2:别人的)
     * @param['groupid'] 否   群组id
     * @param['page']   是   分页id
     *
     * @return Json
     */
    public function get_groupaffiche_list_info(GroupafficheRequest $request)
    {
        $token = (String)$request->input('token', '');
        $typeid = (Int)$request->input('typeid', 0);
        $groupid = (Int)$request->input('group_id', 0);
        $page = (Int)$request->input('page', 1);

        // 验证参数
        if (!intval($groupid))
        {
            return JsonBuilder::Error('参数错误');
        }
        // 验证参数是否正确
        if (!in_array($typeid, [1, 2]))
        {
            return JsonBuilder::Error('类型值错误');
        }

        $user = $request->user();
        $user_id = $user->id;

        // 类型(1:自己的,2:别人的)
        $typeid = $typeid == 1 ? 1 : 2;

        // 获取的字段
        $fieldArr = [
            'icheid', 'user_id', 'school_id' , 'iche_type', 'iche_title',
            'iche_content', 'iche_view_num', 'iche_share_num',
            'iche_praise_num', 'iche_comment_num', 'created_at'
        ];

        $infos = [];

        // 实例化模型类
        $afficheobj = new AfficheDao();

        // 查询条件（查看自己的）
        if ( $typeid == 1 ) {
            $data = Affiche::where('user_id', '=', $user_id)
                ->whereIn('status', [-1, 1, 2])
                ->where('minx_id', '=', $groupid)
                ->where('cate_id', '=', 2)
                ->orderBy('icheid', 'desc')
                ->offset($afficheobj->offset($page))
                ->limit($afficheobj::$limit)
                ->select($fieldArr)
                ->get();
            $infos = !empty($data->toArray()) ? $data->toArray() : [];
        }

        // 查看别人的
        if ( $typeid == 2 )
        {
            $data = Affiche::where('status', '=', 1)
                ->where('minx_id', '=', $groupid)
                ->where('cate_id', '=', 2)
                ->orderBy('icheid', 'desc')
                ->offset($afficheobj->offset($page))
                ->limit($afficheobj::$limit)
                ->select($fieldArr)
                ->get();
            $infos = !empty($data->toArray()) ? $data->toArray() : [];
        }

        if (!empty($infos))
        {
            foreach ($infos as $key => &$val)
            {
                $praiseobj = new PraiseDao();
                $userFollowobj = new UserFollowDao();
                // 是否关注
                $isfollow = (Int)$userFollowobj->getUserFollowCount($user_id, $val['user_id']);
                // 是否点赞
                $ispraise = (Int)$praiseobj->getAffichePraiseCount(1,$user_id, $val['icheid']);
                // 用户信息
                $userInfo = $afficheobj->userInfo($val['user_id']);
                // 用户信息
                $userInfo = $afficheobj->userInfo($val['user_id']);
                $val['user_pics'] = (String)$userInfo['user_pics'];
                $val['user_nickname'] = (String)$userInfo['user_nickname'];
                $val['school_name'] = (String)$userInfo['school_name'];

                // 格式化时间
                $val['create_timestr'] = $afficheobj->transTime1(strtotime($val['created_at']));

                // 图片信息
                $picsList = $afficheobj->getAffichePicsListInfo($val['icheid']);
                // 视频信息
                $videoInfo = $afficheobj->getAfficheVideoOneInfo($val['icheid']);
                // 合并数据
                $val = array_merge($val, ['isfollow'=>$isfollow,'ispraise'=>$ispraise,'picsList' => $picsList,'videoInfo' => $videoInfo]);
            }
        }

        return JsonBuilder::Success ( $infos , '社群动态列表' );
    }

}
