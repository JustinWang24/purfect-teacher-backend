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

use App\Models\Affiche\AfficheComment; // 动态评论模型
use App\Models\Affiche\BbsMessage; // 用户消息
use Illuminate\Support\Facades\DB;
class MessageDao extends \App\Dao\Affiche\CommonDao
{
    public function __construct()
    {
    }

    /**
     * Func 添加动态评论信息
     *
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function addAfficheCommentsInfo($data = [])
    {
        if (empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if ($obj = AfficheComment::create($data)) {
                DB::commit();
                return $obj->id;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Func 更新动态字段信息
     *
     * @param $id 更新Id
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function editAfficheCommentsInfo($data = [], $id = 0)
    {
        if (!intval($id) || empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if (AfficheComment::where('commentid',$id)->update($data)) {
                DB::commit();
                return $id;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Func 获取动态评论列表
     *
     * @param['user_id']  是  用户id
     * @param['iche_id']  是  动态id
     * @param['page'] 是   分页id
     *
     * @return array
     */
    public function getAfficheCommentListInfo($user_id = 0 , $iche_id = 0 , $page = 1)
    {
        if (!intval($iche_id))
        {
            return [];
        }

        // 检索条件
        $condition[] = ['iche_id', '=', (Int)$iche_id];
        $condition[] = ['comment_pid', '=', 0];
        $condition[] = ['status', '=', 1];

        $data = AfficheComment::from('affiche_comments as a')
            ->where($condition)->select(['a.*'])
            ->orderBy('commentid', 'desc')
            ->offset($this->offset($page))
            ->limit(self::$limit)
            ->get();

        $data =  !empty($data->toArray()) ? $data->toArray() : [];

       if(!empty($data) && is_array($data))
       {
            foreach($data as $key=>&$val)
            {
                // 未登录和已登录点赞
                $val['ispraise'] = 0;
                if ($user_id) {
                    $ispraise = (Int)(new PraiseDao())->getAffichePraiseCount(2, $user_id, $val['commentid']);
                }

                // 获取互评信息
                $condition1 = [];
                $condition1[] = ['status', '=', 1];
                $condition1[] = ['comment_pid', '=', $val[ 'commentid' ]];

                $commentList = AfficheComment::where ( $condition1 )->orderBy('commentid', 'asc')->get();

                $val[ 'replyList' ] = !empty($commentList->toArray()) ? $commentList->toArray() : [];
            }
       }
       return $data;
    }

    /**
     * Func 获取动态评论详情
     *
     * @param['iche_id']  是  动态id
     * @param['commentid']  是  评论id
     *
     * @return array
     */
    public function getAfficheCommentOneInfo($commentid = 0)
    {
        if (!intval($commentid))
        {
            return [];
        }

        // 检索条件
        $condition[] = ['commentid', '=', (Int)$commentid];
        $condition[] = ['status', '=', 1];

        $data = AfficheComment::where($condition)->first(['*']);

        $data = !empty($data) ? $data->toArray() : [];

        return $data;
    }


    /**
     * Func 删除评论
     *
     * @param['iche_id']  是  动态id
     * @param['commentid']  是  评论id
     *
     * @return array
     */
    public function delAfficheCommentOneInfo($commentid = 0)
    {
        if (!intval($commentid))
        {
            return [];
        }
        // 检索条件
        $condition[] = ['commentid', '=', (Int)$commentid];
        return AfficheComment::where($condition)->delete();
    }

    /**
     * Func 分析评论人所参与的人的信息
     *  返回干系人用户Userid 和 评论动态的基础信息
     *
     * @param $userInfo    当前用户信息
     * @param $afficheInfo 动态的信息
     * @param $commentid   评论的ID
     *
     * return ['content'=>'消息标题显示的内容','autherIdArr'=>[[影响的用户id]]
     */
    public function getApiAfficheCommentAutherInfo ( array $userInfo , array $afficheInfo , $commentid = 0 )
    {
        // 层级动态文字提示
        $levelMessageArr = [
            1 => '评论了你发布的动态' ,
            2 => '评论了你参与的动态评论' ,
            3 => '回复了你参与过的动态评论',
        ];

        $returnArr = [];

        if ( intval ( $commentid ) || !empty( $afficheInfo ) )
        {
            // 获取动态评论信息
            $commentOneInfo = $this->getAfficheCommentOneInfo($commentid);

            if ( ! empty( $commentOneInfo ) && is_array ( $commentOneInfo ) )
            {
                // 一级评论，对动态的评论，需要告知动态一级评论人
                if ( $commentOneInfo[ 'comment_levid' ] == 0 )
                {
                    $levelArr = [1, 2];
                    // 检索条件
                    $condition[] = ['comment_levid', '=', 0];
                    $condition[] = ['iche_id', '=', $afficheInfo['icheid']];
                    $condition[] = ['commentid', '=', $commentOneInfo['commentid']];
                }

                // 二级评论，对动态的评论，进行评论告知评论下的所有人
                if ( $commentOneInfo[ 'comment_levid' ] > 0 )
                {
                    // 二级评论和三级评论的处理信息
                    $commentTwoInfo = $this->getAfficheCommentOneInfo($commentOneInfo[ 'comment_pid' ] );

                    if ( $commentTwoInfo )
                    {
                        // 二级检索条件，和显示的文字信息
                        if ( $commentTwoInfo[ 'comment_pid' ] == 0 )
                        {
                            $levelArr = [3, 2];
                            $condition[] = ['iche_id', '=', $afficheInfo['icheid']];
                            $condition[] = ['commentid', '', $commentOneInfo['commentid']];
                            $condition[] = ['comment_levid', '', $commentOneInfo['comment_pid']];
                        }
                        // 三级以上互评,显示的文字信息
                        if ( $commentTwoInfo[ 'comment_pid' ] > 0 ) $levelArr = [ 3 , 2 ];
                    }
                }
                // 获取数据信息
                if ( $condition )
                {
                    $userIdInfo = AfficheComment::where($condition)->select(['user_id'])->get();
                    $returnArr = empty($userIdInfo->toArray()) ? array_column($userIdInfo->toArray(), 'user_id') : [];
                }
            }
        }

        // 返回的信息
        $returnData[ 'content' ]  = (String)$levelMessageArr[ $levelArr[ 0 ] ]; // 内容1
        $returnData[ 'content1' ] = (String)$levelMessageArr[ $levelArr[ 1 ] ]; // 内容2

        $returnData[ 'autherIdArr' ] = $returnArr ? (array)array_filter ( array_unique ( $returnArr ) ) : []; // 受影响的用户

        return $returnData;
    }

    /**
     * Func:  统计数据
     *
     * @Param $condition array 查询条件
     * @Param $field string 获取的字段值
     *
     * 实例：[['iche_id', '=', $infos['icheid'], ['status', '=', 1]]]
     *
     * return Int
     */
    public static function getAfficheMessageStatistics ( $condition = [] , $mode = 'count' , $field = null )
    {
        // 条件/ 排序必须唯一,必传参数
        if ( ! $condition ) return 0;

        if ( in_array ( $mode , [ 'max' , 'min' , 'avg' , 'sum' ] ) && ! $field ) return 0;

        if ( $mode == 'count' ) return AfficheComment::where ( $condition )->count ();
        if ( $mode == 'max' ) return AfficheComment::where ( $condition )->max ( $field );
        if ( $mode == 'min' ) return AfficheComment::where ( $condition )->min ( $field );
        if ( $mode == 'avg' ) return (float)AfficheComment::where ( $condition )->avg ( $field );
        if ( $mode == 'sum' ) return (float)AfficheComment::where ( $condition )->sum ( $field );
        return 0;
    }


    //---------------------------------消息列表-----------------------------------------------------------

    /**
     * Func 添加用户消息
     *
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function addBbsMessageInfo($data = [])
    {
        if (empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if ($obj = BbsMessage::create($data)) {
                DB::commit();
                return $obj->id;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $exception) {
            echo $exception->getMessage();exit;
            DB::rollBack();
            return false;
        }
    }


    /**
     * Func 获取我的消息
     *
     * @param['messageid']  是  消息id
     *
     * @return array
     */
    public function getBbsMessageOneInfo($messageid = 0)
    {
        if (!intval($messageid))
        {
            return [];
        }

        // 检索条件
        $condition[] = ['messageid', '=', (Int)$messageid];
        $condition[] = ['status', '=', 1];

        $data = BbsMessage::where($condition)->first(['*']);

        $data = !empty($data) ? $data->toArray() : [];

        return $data;
    }

    /**
     * Func 我的消息删除
     *
     * @param['messageid']  是  消息id
     *
     * @return array
     */
    public function delBbsMessageOneInfo($messageid = 0)
    {
        if (!intval($messageid))
        {
            return [];
        }
        // 检索条件
        $condition[] = ['messageid', '=', (Int)$messageid];
        return BbsMessage::where($condition)->delete();
    }
}
