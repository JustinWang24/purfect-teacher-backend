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
use App\Http\Requests\Api\Affiche\MessageRequest;

use App\Dao\Users\UserDao; // 用户
use App\Dao\Affiche\Api\AfficheDao; // 动态
use App\Dao\Affiche\Api\MessageDao; // 评论
use App\Dao\Affiche\Api\PraiseDao; // 点赞

use Illuminate\Support\Facades\DB;
use App\BusinessLogic\WifiInterface\Factory;

class PraiseController extends Controller
{
    /**
     * Func 动态和评论点赞接口
     *
     * @param Request $request
     * @param['id'] 是   动态id/评论id
     * @param['type'] 是   类型(1:动态点赞,2:动态评论点赞)
     *
     * @return Json
     */
    public function praise_add_info(MessageRequest $request)
    {
        $token = (String)$request->input('token', '');
        $id = (Int)$request->input('id', 0);
        $type = (Int)$request->input('type', 0);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!intval($id)) {
            return JsonBuilder::Error('参数错误');
        }
        if (!in_array($type,[1,2])) {
            return JsonBuilder::Error('类型错误');
        }

        $user = $request->user();
        $user_id = $user->id;
        $campus_id = $user->gradeUser->campus_id;

        // 动态点赞
        if($type == 1)
        {
            // 实例化模型类
            $afficheobj = new AfficheDao();
            $afficheInfo = $afficheobj->getAfficheOneInfo($id);
            if(!$afficheInfo)
            {
                return JsonBuilder::Error('动态不存在');
            }

            // 添加点赞
            $praiseobj = new PraiseDao();
            $infos = $praiseobj->addOrUpdateApiAffichePraiseInfo($type, $user_id, $id);

            // 更新动态点赞总数
            $count = $praiseobj->getAffichePraiseTotal($type, $afficheInfo['icheid']);

            $saveData['iche_praise_num'] = $count;
            $saveData['updated_at'] = date('Y-m-d H:i:s');
            if($afficheobj->editAffichesInfo($saveData, $afficheInfo['icheid']))
            {
                // 增加消息提醒
                // 图片信息
                $picsList = $afficheobj->getAffichePicsListInfo($afficheInfo['icheid']);
                if (!isset($picsList[0]['pics_smallurl'])) {
                    $picsList[0]['pics_smallurl'] = '';
                }

                // 视频信息
                $videoInfo = $afficheobj->getAfficheVideoOneInfo($afficheInfo['icheid']);
                if (!isset($videoInfo['cover_url'])) {
                    $videoInfo['cover_url'] = '';
                }

                // 获取用户信息
                $userInfo1 = $afficheobj->userInfo($user_id); // 评论人用户信息

                // 获取要添加点赞消息的数据
                $returnArr = $praiseobj->getAffichePraiseAutherInfo (
                    [ 'typeid' => 1 , 'userInfo' => $userInfo1 , 'afficheInfo' => $afficheInfo , 'minxid' => 0 ]
                );

                // 添加点赞消息
                $addMessageData['mess_type1'] = 2;
                $addMessageData['mess_type2'] = 2;
                $addMessageData['mess_mixid'] = $afficheInfo['icheid'];
                $addMessageData['mess_content'] = (String)$returnArr['content'];
                $addMessageData['mess_content1'] = (String)$returnArr['content1'];
                $addMessageData['mess_type3'] = $afficheInfo['iche_type'] != 'video' ? 1 : 2;
                $addMessageData['mess_pics'] = $afficheInfo['iche_type'] != 'video' ? (String)$picsList[0]['pics_smallurl'] : (String)$videoInfo['cover_url'];;
                $addMessageData['user_id'] = (Int)$userInfo1['user_id'];
                $addMessageData['user_pics'] = (String)$userInfo1['user_pics'];
                $addMessageData['user_nickname'] = (String)$userInfo1['user_nickname'];
                $addMessageData['touser_id'] = (Int)$returnArr['touserInfo']['user_id'];
                $addMessageData['touser_pics'] = (String)$returnArr['touserInfo']['user_pics'];
                $addMessageData['touser_nickname'] = (String)$returnArr['touserInfo']['user_nickname'];

                $messageobj = new MessageDao();
                $messageid = $messageobj->addBbsMessageInfo($addMessageData);
                if ( $messageid != false)
                {
                    // TODO....推送消息并增加红点操作
                    // D ( 'Apibbsmessage' )->addOrSendApiBbsMessageOneInfo ( $messageid );
                }
            }

            return JsonBuilder::Success($infos, '操作成功');

        }

        // 动态评论点赞
        if($type == 2)
        {
            // 实例化模型类
            $messageobj = new MessageDao();
            $data = $messageobj->getAfficheCommentOneInfo($id);
            if(!$data)
            {
                return JsonBuilder::Error('评论不存在');
            }

            // 添加点赞
            $praiseobj = new PraiseDao();
            $infos = $praiseobj->addOrUpdateApiAffichePraiseInfo($type, $user_id, $data['commentid']);

            // 更新动态点赞总数
            $count = $praiseobj->getAffichePraiseTotal($type, $data['commentid']);

            $saveData['comment_praise'] = $count;
            $saveData['updated_at'] = date('Y-m-d H:i:s');

            if($messageobj->editAfficheCommentsInfo($saveData, $data['commentid']))
            {
                // 增加消息提醒
                $afficheobj = new AfficheDao();
                $afficheInfo = $afficheobj->getAfficheOneInfo($data['iche_id']);
                // 图片信息
                $picsList = $afficheobj->getAffichePicsListInfo($afficheInfo['icheid']);
                if (!isset($picsList[0]['pics_smallurl'])) {
                    $picsList[0]['pics_smallurl'] = '';
                }

                // 视频信息
                $videoInfo1 = $afficheobj->getAfficheVideoOneInfo($afficheInfo['icheid']);
                if (!isset($videoInfo['cover_url'])) {
                    $videoInfo['cover_url'] = '';
                }

                // 获取用户信息
                $userInfo1 = $afficheobj->userInfo($user_id); // 评论人用户信息

                // 获取要添加点赞消息的数据
                $returnArr = $praiseobj->getAffichePraiseAutherInfo (
                    [ 'typeid' => 2 , 'userInfo' => $userInfo1 , 'afficheInfo' => $afficheInfo , 'minxid' => $data['commentid'] ]
                );

                // 添加点赞消息
                $addMessageData[ 'mess_type1' ]      = 2;
                $addMessageData[ 'mess_type2' ]      = 2;
                $addMessageData[ 'mess_mixid' ]      = $afficheInfo[ 'icheid' ];
                $addMessageData[ 'mess_content' ]   = (String)$returnArr[ 'content' ];
                $addMessageData[ 'mess_content1' ]   = (String)$returnArr[ 'content1' ];
                $addMessageData[ 'mess_type3' ]      = $afficheInfo[ 'iche_type' ] != 'video' ? 1 : 2;
                $addMessageData[ 'mess_pics' ]       = $afficheInfo[ 'iche_type' ] != 'video' ? (String)$picsList[ 0 ][ 'pics_smallurl' ] : (String)$videoInfo[ 'cover_url' ];;
                $addMessageData[ 'user_id' ]          = (Int)$userInfo1[ 'user_id' ];
                $addMessageData[ 'user_pics' ]       = (String)$userInfo1[ 'user_pics' ];
                $addMessageData[ 'user_nickname' ]   = (String)$userInfo1[ 'user_nickname' ];
                $addMessageData[ 'touser_id' ]        = (Int)$returnArr['touserInfo'][ 'user_id' ];
                $addMessageData[ 'touser_pics' ]     = (String)$returnArr['touserInfo'][ 'user_pics' ];
                $addMessageData[ 'touser_nickname' ] = (String)$returnArr['touserInfo'][ 'user_nickname' ];

                $messageobj = new MessageDao();
                $messageid = $messageobj->addBbsMessageInfo($addMessageData);
                if ( $messageid != false)
                {
                    // TODO....推送消息并增加红点操作
                    // D ( 'Apibbsmessage' )->addOrSendApiBbsMessageOneInfo ( $messageid );
                }
            }
            return JsonBuilder::Success($infos, '操作成功');
        }
    }
}
