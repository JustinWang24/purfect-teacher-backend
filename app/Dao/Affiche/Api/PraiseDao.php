<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Dao\Affiche\Api;


use App\Utils\JsonBuilder;
use Illuminate\Support\Collection;
use App\Utils\ReturnData\MessageBag;

use App\Models\Affiche\AffichePraise; // 点赞
use App\Models\Affiche\AfficheComment; // 动态评论模型
use App\Models\Affiche\BbsMessage; // 用户消息
use Illuminate\Support\Facades\DB;

class PraiseDao extends \App\Dao\Affiche\CommonDao
{
    public function __construct()
    {
    }

    /**
     * Func 获取动态和评论是否点赞
     *
     * @param['typeid']  是   类型(1:动态,2:评论)
     * @param['user_id']  是  用户id
     * @param['minx_id'] 是   动态id或者评论id
     *
     * @return bool
     */
    public function getAffichePraiseCount($typeid = 1 , $user_id = 0 , $minx_id = 0)
    {
        if( !intval($user_id) || !intval($minx_id))
        {
            return 0;
        }
        // 检索条件
        $condition[] = ['typeid','=',$typeid];
        $condition[] = ['user_id','=',$user_id];
        $condition[] = ['minx_id','=',$minx_id];
        $count = AffichePraise::where($condition)->count();

        if($count > 0){
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Func 添加和取消赞
     *
     * @param['typeid']  是   类型(1:动态,2:评论)
     * @param['user_id']  是  用户id
     * @param['minx_id'] 是   动态id或者评论id
     *
     * @return bool
     */
    public function addOrUpdateApiAffichePraiseInfo($typeid = 1 , $user_id = 0 , $minx_id = 0)
    {
        if ( !intval( $typeid ) || !intval( $user_id ) || !intval( $minx_id ) )
        {
            return [ 'result' => 0 ];
        }

        // 检索条件
        $condition[] = ['typeid','=',$typeid];
        $condition[] = ['user_id','=',$user_id];
        $condition[] = ['minx_id','=',$minx_id];
        $count = AffichePraise::where($condition)->count();
        // 取消赞
        if($count > 0)
        {
            // 取消赞
            if(AffichePraise::where($condition)->delete())
            {
                return [ 'result' => 0 ];
            } else {
                return [ 'result' => 1 ];
            }
        } else{
            // 添加赞
            $addData['typeid'] = $typeid;
            $addData['minx_id'] = $minx_id;
            $addData['user_id'] = $user_id;
            $addData['created_at'] = date('Y-m-d H:i:s');
            if(AffichePraise::create($addData))
            {
                return [ 'result' => 1 ];
            } else {
                return [ 'result' => 0 ];
            }
        }
        return [ 'result' => 0 ];
    }


    /**
     * Func: 统计数据
     *
     * @Param $typeid 是 类型(1:动态,2:评论)
     * @Param $iche_id 是 动态id
     *
     * return Int
     */
    public function getAffichePraiseTotal ( $typeid = 1 , $iche_id = 0 )
    {
        if ( ! intval($iche_id) ) return 0;

        // 查询条件
        $condition[] = ['minx_id','=',$iche_id];
        $condition[] = ['typeid','=',$typeid];

        return (Int)AffichePraise::where($condition)->count();
    }

    /**
     * Func 分析点赞人所参与的人的信息
     *  返回干系人用户Userid 和 评论动态的基础信息
     *
     * @param $param ['typeid']         1:动态点赞,2:动态评论点赞
     * @param $param ['userInfo']       当前用户信息
     * @param $param ['afficheInfo']    动态的信息
     * @param $param ['minxid']         评论表中的互评ID
     *
     * return ['content'=>'消息标题显示的内容','autherIdArr'=>[[影响的用户id]]
     */
    public function getAffichePraiseAutherInfo ( $param )
    {
        // 层级动态文字提示
        $levelMessageArr = [
            1 => '给你发布的动态信息点赞啦！' ,
            2 => '给你评论的信息点赞啦！' ,
        ];

        // 动态点赞
        if ( intval ( $param['typeid'] ) == 1 && ! empty( $param['afficheInfo'] ) )
        {
            $levelArr = [ 1 , 1 ];
            // 获取动态发布者用户信息
            $userInfo = $this->userInfo($param[ 'afficheInfo' ][ 'user_id' ]);
        }

        // 互评点赞
        if ( intval ( $param['typeid'] ) == 2 && ! empty( $param['afficheInfo'] ) )
        {
            $levelArr = [ 2 , 2 ];
            // 获取互评评论的评论
            $praiseOneInfo = AfficheComment::where('commentid','=', $param['minxid'])->first();
            // 获取动态发布者用户信息
            $userInfo = $this->userInfo($praiseOneInfo[ 'user_id' ]);
        }

        // 返回的信息
        $returnData[ 'content' ]    = (String)$levelMessageArr[ $levelArr[ 0 ] ]; // 内容1
        $returnData[ 'content1' ]   = (String)$levelMessageArr[ $levelArr[ 1 ] ]; // 内容2
        $returnData[ 'touserInfo' ] = (array)$userInfo; // 收到者用户信息

        return $returnData;
    }
}
