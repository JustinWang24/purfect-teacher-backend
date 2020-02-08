<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/31
 * Time: 11:33 AM
 */
namespace App\Http\Controllers\Api\Affiche;


use App\Models\Affiche\AfficheComment;
use Psy\Util\Json;
use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Affiche\MessageRequest;

use App\Dao\Users\UserDao; // 用户
use App\Dao\Affiche\Api\AfficheDao; // 动态
use App\Dao\Affiche\Api\MessageDao; // 评论
use App\Models\Affiche\BbsMessage;

use Illuminate\Support\Facades\DB;
use App\BusinessLogic\WifiInterface\Factory;

class MessageController extends Controller
{

    /**
     * Func 动态评论和评论互评接口
     *
     * @param Request $request
     * @param['id'] 是   动态id
     * @param['commentid'] 是   评论id
     * @param['com_content']   是   评论内容
     *
     * @return Json
     */
   public function message_add_info(MessageRequest $request)
   {
       $token = (String)$request->input('token', '');
       $icheid = (Int)$request->input('icheid', 0);
       $commentid = (Int)$request->input('commentid', 0);
       $com_content = (String)$request->input('com_content', '');

       if ($token == '') {
           return JsonBuilder::Error('请先登录');
       }
       if (!intval($icheid)) {
           return JsonBuilder::Error('动态参数错误');
       }
       if ($com_content == '') {
           return JsonBuilder::Error('评论内容不能为空');
       }

       $user = $request->user();
       $user_id = $user->id;
       $campus_id = $user->gradeUser->campus_id;

       // 实例化模型类
       $afficheobj = new AfficheDao();
       $infos = $afficheobj->getAfficheOneInfo($icheid);
       if (empty($infos))
       {
           return JsonBuilder::Error('动态不存在或者已关闭');
       }

       // 对评论进行回复操作
       $messageobj = new MessageDao();
       if ($commentid)
       {
           $data = $messageobj->getAfficheCommentOneInfo($commentid);
           if (empty($data))
           {
               return JsonBuilder::Error('评论参数错误');
           }
       }

       // 获取用户信息
       $userInfo1 = $afficheobj->userInfo($user_id); // 评论人用户信息
       $userInfo2 = $afficheobj->userInfo(isset($data['user_id']) ? $data['user_id'] : $infos['user_id']); // 评论人信息
       $userInfo3 = $afficheobj->userInfo($infos['user_id']); // 动态发布人信息

       // 添加数据
       $addData['iche_id'] = $infos['icheid'];
       $addData['iche_type'] = $infos['iche_type'];
       $addData['com_content'] = strip_tags($com_content);
       $addData['user_id'] = (Int)$userInfo1['user_id'];
       $addData['user_pics'] = (String)$userInfo1['user_pics'];
       $addData['user_nickname'] = (String)$userInfo1['user_nickname'];
       $addData['touser_id'] = (Int)$userInfo2['user_id'];
       $addData['touser_pics'] = $userInfo2['user_pics'] ? (String)$userInfo2['user_pics'] : $userInfo3['user_pics'];
       $addData['touser_nickname'] = $userInfo2['user_nickname'] ? (String)$userInfo2['user_nickname'] : $userInfo3['user_nickname'];
       $addData['comment_pid'] = isset($data['commentid']) ? $data['commentid'] : 0; // 父级id(0顶级)
       $addData['comment_levid'] = isset($data['comment_levid']) ? $data['comment_levid'] : (isset($data['commentid']) ? (int)$data['commentid'] : 0); // 记录顶级commentid
       $commentid = $messageobj->addAfficheCommentsInfo($addData);
       if($commentid != false)
       {
           // 更新动态的评论数
           $commentid1[] = ['iche_id', '=', $infos['icheid']];
           $commentid1[] = ['comment_levid', '=', 0];
           $commentid1[] = ['status', '=', 1];
           $count = $messageobj->getAfficheMessageStatistics($commentid1);
           $afficheobj->editAffichesInfo(['iche_comment_num' => $count, 'updated_at' => date('Y-m-d H:i:s')], $infos['icheid']);
           $user_id = 18882;
           // 增加消息数据,如果一级评论,过滤自己收到消息,如果是二级评论，过滤我自己回复消息
           if ( ( ! $data && $user_id != $infos[ 'user_id' ] ) || ( $data && $data[ 'user_id' ] != $user_id ) )
           {
               // 获取动态的标题文字信息和收到的用户信息。
               $returnArr = $messageobj->getApiAfficheCommentAutherInfo ( $userInfo1 , $infos , $commentid );

               // 获取动态的图片或者视频封面图
               // 图片信息
               $picsListInfo = $afficheobj->getAffichePicsListInfo($infos['icheid']);
               $picsList[0]['pics_smallurl'] = !empty($picsList[0]['pics_smallurl']) ? $picsListInfo[0]['pics_smallurl'] : '';
               // 视频信息
               $videoInfo = $afficheobj->getAfficheVideoOneInfo($infos['icheid']);
               $videoInfo['cover_url'] = !empty($videoInfo['cover_url']) ? $videoInfo['cover_url'] : '';

               // 添加首页消息记录
               $addMessageData[ 'mess_type1' ]      = 2;
               $addMessageData[ 'mess_type2' ]      = 1;
               $addMessageData[ 'mess_mixid' ]      = $infos[ 'icheid' ];
               $addMessageData[ 'mess_content' ]    = (String)$returnArr[ 'content' ];
               $addMessageData[ 'mess_content1' ]   = (String)$returnArr[ 'content1' ];
               $addMessageData[ 'mess_type3' ]      = $infos[ 'iche_type' ] != 'video' ? 1 : 2;
               $addMessageData[ 'mess_pics' ]       = $infos[ 'iche_type' ] != 'video' ? (String)$picsList[ 0 ][ 'pics_smallurl' ] : (String)$videoInfo[ 'cover_url' ];
               $addMessageData[ 'mess_commentid' ]  = $commentid;   // 互评id
               $addMessageData[ 'user_id' ]          = (Int)$userInfo1[ 'user_id' ];
               $addMessageData[ 'user_pics' ]       = (String)$userInfo1[ 'user_pics' ];
               $addMessageData[ 'user_nickname' ]   = (String)$userInfo1[ 'user_nickname' ];
               $addMessageData[ 'touser_id' ]        = (Int)$userInfo2[ 'user_id' ];
               $addMessageData[ 'touser_pics' ]     = (String)$userInfo2[ 'user_pics' ];
               $addMessageData[ 'touser_nickname' ] = (String)$userInfo2[ 'user_nickname' ];
               $addMessageData[ 'mess_content2' ]   = (String)strip_tags ( $com_content );
               $addMessageData[ 'autherids' ]       = (String)implode ( ',' , $returnArr[ 'autherIdArr' ] );

               $messageid = $messageobj->addBbsMessageInfo($addMessageData);

               if ( $messageid != false)
               {
                   // TODO....推送消息并增加红点操作
                   // D ( 'Apibbsmessage' )->addOrSendApiBbsMessageOneInfo ( $messageid );
               }
           }
           $return = $messageobj->getAfficheCommentOneInfo($commentid);

           return JsonBuilder::Success($return,'评论成功');

       } else {
           return JsonBuilder::Error('评论失败，请稍后重试');
       }
   }

    /**
     * Func 动态删除评论接口
     *
     * @param Request $request
     * @param['token'] 是   token
     * @param['commentid'] 是   评论id
     *
     * @return Json
     */
    public function message_del_info(MessageRequest $request)
    {
        $token = (String)$request->input('token', '');
        $commentid = (Int)$request->input('commentid', 0);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!intval($commentid)) {
            return JsonBuilder::Error('参数错误');
        }

        $user = $request->user();
        $user_id = $user->id;
        $campus_id = $user->gradeUser->campus_id;


        $messageobj = new MessageDao();

        $getAfficheCommentOneInfo = $messageobj->getAfficheCommentOneInfo($commentid);
        if(empty($getAfficheCommentOneInfo))
        {
            return JsonBuilder::Error('删除信息不存在');
        }

        // 是否有权限删除
        if ( $getAfficheCommentOneInfo[ 'user_id' ] != $user_id )
        {
            return api_response_error('','你无权删除');
        }

        if ( $messageobj->delAfficheCommentOneInfo($getAfficheCommentOneInfo['commentid']) )
        {

            // 更新动态的评论数
            $commentid1[] = ['iche_id', '=', $getAfficheCommentOneInfo['iche_id']];
            $commentid1[] = ['comment_levid', '=', 0];
            $commentid1[] = ['status', '=', 1];

            $count = $messageobj->getAfficheMessageStatistics($commentid1);
            if ($count > 0) {
                // 更新数据
                $saveData['iche_comment_num'] = $count;
                (new AfficheDao())->editAffichesInfo($saveData, $getAfficheCommentOneInfo['iche_id']);
            }

            return JsonBuilder::Success('操作成功');

        }else{
            return api_response_error('','操作失败,请稍后重试');
        }
    }


    /**
     * Func 我的消息列表接口
     *
     * @param Request $request
     * @param['token'] 是  token
     * @param['page']  是  分页id
     *
     * @return Json
     */
    public function message_list_info(MessageRequest $request)
    {
        $token = (String)$request->input('token', '');
        $page = (Int)$request->input('page', 1);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }

        $user = $request->user();
        $user_id = $user->id;

        $messageobj = new MessageDao();

        // 检索条件
        $condition[] = ['touser_id', '=', $user_id];
        $condition[] = ['status', '=', 1];

        $data = BbsMessage::where($condition)
            ->orderBy('messageid', 'desc')
            ->offset($messageobj->offset($page))
            ->limit($messageobj::$limit)
            ->get();

        $infos = !empty($data->toArray()) ? $data->toArray() : [];

        return JsonBuilder::Success($infos,'我的消息列表接口');
    }

    /**
     * Func 我的消息删除接口
     *
     * @param Request $request
     * @param['token']  是   token
     * @param['messageid'] 是   消息id
     *
     * @return Json
     */
    public function message_reset_info(MessageRequest $request)
    {
        $token = (String)$request->input('token', '');
        $messageid = (Int)$request->input('messageid', 0);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!intval($messageid)) {
            return JsonBuilder::Error('参数错误');
        }

        $user = $request->user();
        $user_id = $user->id;

        $messageobj = new MessageDao();
        $getBbsMessageOneInfo = $messageobj->getBbsMessageOneInfo($messageid);

        if (empty($getBbsMessageOneInfo)) {
            return JsonBuilder::Error('删除的消息不存在');
        }

        // 是否有权限删除
        if ($getBbsMessageOneInfo['touser_id'] != $user_id) {
            return JsonBuilder::Error('你无权删除');
        }

        if ($messageobj->delBbsMessageOneInfo($messageid))
        {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败,请稍后重试');
        }
    }

}
