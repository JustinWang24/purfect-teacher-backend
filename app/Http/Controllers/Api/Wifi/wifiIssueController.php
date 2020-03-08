<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 19/11/19
 * Time: 11:33 AM
 */
namespace App\Http\Controllers\Api\Wifi;

use Psy\Util\Json;
use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache; // 缓存
use App\Http\Requests\Api\Wifi\WifiIssueRequest; // Wifi报修请求

use App\Dao\Wifi\Api\WifiIssueTypesDao; // 报修类型
use App\Dao\Wifi\Api\WifiIssuesDao; // 报修单
use App\Dao\Wifi\Api\WifiIssueDisposesDao; // 报修单回复
use App\Dao\Wifi\Api\WifiIssueCommentsDao; // 报修评论

class wifiIssueController extends Controller
{
   /**
    * Func 报修类型
    * @param WifiIssueRequest $request
    * @return json
    */
   public function list_category_info ( WifiIssueRequest $request )
   {
      // 获取状态
      $condition[] = [ 'purpose' , '=' , 1 ]; // 用途（1:客户选择故障类型,2:维修人员类型选择故障)
      $condition[] = [ 'status' , '=' , 1 ]; // 状态(0:不显示,1:显示)

      // 获取的字段信息
      $fieldArr = [ 'typeid' , 'type_name' , 'type_name' , 'type_pid' ];

      $infos = WifiIssueTypesDao::getWifiIssueTypesListInfo (

         $condition , [ 'typeid' , 'asc' ] , [ 'page' => 1 , 'limit' => 500 ] , $fieldArr

      )->toArray()['data'];

      if(!empty($infos)) $infos = WifiIssueTypesDao::cateTree( $infos , 'typeid' , 'type_pid' );

      return JsonBuilder::Success ( $infos ,'报修类型列表');

   }

   /**
    * Func 添加报修
    * @param WifiIssueRequest $request
    * @return json
    */
   public function add_issue_info( WifiIssueRequest $request )
   {
      $user = $request->user ();

      $param = $request->only (
         [
            'typeone_id' , 'typeone_name' , 'typetwo_id' , 'typetwo_name' ,
            'issue_name' , 'issue_mobile' ,'addressoneid' , 'addresstwoid' ,
            'addr_detail' , 'issue_desc'
         ]
      );

      // 表单要插入的字段信息
      $param1 = self::getPostParamInfo ( $param , [
         'typeone_id' , 'typeone_name' , 'typetwo_id' , 'typetwo_name' , 'issue_name' ,
         'issue_mobile' , 'issue_desc' , 'addressoneid' , 'addresstwoid' , 'addressthreeid' ,
         'addr_detail'
         ]
      );

      // 更新的数据
      $param2[ 'user_id' ]   = $user->id; // 用户id
      $param2[ 'trade_sn' ]  = date ( 'Ymd' ).random_int(10000,99999);; // 编号
      $param2[ 'school_id' ] = $user->gradeUserOneInfo->school_id; // 学校
      $param2[ 'campus_id' ] = $user->gradeUserOneInfo->campus_id; // 校区

      // 获取详细地址
      $getRoomOneInfo1 = WifiIssueTypesDao::getBuildOneInfo ( $param1[ 'addressoneid' ] );
      $getRoomOneInfo2 = WifiIssueTypesDao::getRoomOneInfo ( $param1[ 'addresstwoid' ] );
      $address = '';
      if(isset($getRoomOneInfo1)) $address .= $getRoomOneInfo1->name;
      if(isset($getRoomOneInfo2)) $address .= $getRoomOneInfo2->name;
      $param2[ 'addr_detail' ]  = $address.$param1[ 'addr_detail' ];

      // 验证数据是否重复提交
      $dataSign = sha1 ( $user->id . 'add_issue_info' );

      if ( Cache::has ( $dataSign ) ) return JsonBuilder::Error ( '您提交太快了，先歇息一下。' );

      if ( WifiIssuesDao::addOrUpdateWifiIssuesInfo ( array_merge ( $param1 , $param2 ) ) )
      {
         // 生成重复提交签名
         Cache::put ( $dataSign , $dataSign , 10 );

         return JsonBuilder::Success ( '申请成功,等待反馈' );

      } else {

         return JsonBuilder::Error ( '操作失败,请稍后重试' );
      }
   }

   /**
    * Func 我的报修列表
    * @param WifiIssueRequest $request
    * @return json
    */
   public function list_my_issue_info(WifiIssueRequest $request)
   {
      $user = $request->user ();

      // 获取参数
      $param = $request->only ( [ 'page' ] );

      // 查询数据
      $condition[] = ['status','>',0];
      $condition[] = ['user_id','=',$user->id];

	  // 获取的字段信息
      $fieldsArr = [
         'issueid','trade_sn','typeone_id','typeone_name','typetwo_id',
         'typetwo_name','issue_name','issue_mobile','issue_desc','addressoneid',
         'addresstwoid','addressthreeid','addr_detail','admin_name','admin_mobile',
         'admin_desc','status','is_comment', 'created_at'
      ];

	  // 获取报修数据
      $infos = WifiIssuesDao::getWifiIssuesListInfo (
         $condition,['issueid','desc'],['page'=>$param['page'],'limit'=>10],$fieldsArr
      )->toArray()['data'];

      // 处理数据
	  if ( !empty( $infos ) && is_array( $infos ) )
      {
         foreach ($infos as $key => &$val)
         {
            if ($val['status'] == 3 )
            {
               $val['status_str'] = $val['is_comment']==1 ? WifiIssuesDao::$commentArr[1] : WifiIssuesDao::$commentArr[2];
            } else {
               $val['status_str'] = WifiIssuesDao::$statusArr[$val[ 'status' ]];
            }
         }
      }
      return JsonBuilder::Success ( $infos , '我的报修列表' );
   }

   /**
    * Func 获取报修详情
    * @param WifiIssueRequest $request
    * @return json
    */
   public function get_my_issue_info(WifiIssueRequest $request)
   {
      $user = $request->user ();
      // 获取参数
      $param = $request->only ( [ 'issueid'] );

      // 查询数据
      $condition[] = [ 'issueid' , '=' , $param[ 'issueid' ] ];
      $condition[] = [ 'user_id' , '=' , $user->id ];

      // 获取数据
      $getWifiIssuesOneInfo = WifiIssuesDao::getWifiIssuesOneInfo ( $condition,['issueid','desc'],['*']);

      $infos = $getWifiIssuesOneInfo != null ? $getWifiIssuesOneInfo->toArray() : null;

      if ( $infos )
      {
         if ( $infos[ 'status' ] == 3 )
         {
            $infos['status_str'] = $infos['is_comment']==1 ? WifiIssuesDao::$commentArr[1] : WifiIssuesDao::$commentArr[2];
         } else {
            $infos['status_str'] = WifiIssuesDao::$statusArr[$infos[ 'status' ]];
         }

         // 获取状态流程时间
         $data = [
            ['order_title'=>'报修时间','order_time'=>$infos['created_at']],
            ['order_title'=>'接单时间','order_time'=>$infos['jiedan_time']],
            ['order_title'=>'修复时间','order_time'=>$infos['chulis_time']],
         ];
         foreach ( $data as $key => $val )
         {
            if ( strtotime ($val[ 'order_time' ]) <= 0 ) unset( $data[ $key ] );
         }
         $infos[ 'timeArr' ] = (array)array_values( $data );
      }
      return JsonBuilder::Success (  $infos , '单个报修详情' );
   }

   /**
    * Func 添加评论
    * @param WifiIssueRequest $request
    * @return json
    */
   public function add_issue_comment_info(WifiIssueRequest $request)
   {
      $user = $request->user ();
      // 获取参数
      $param = $request->only (
         [
            'issueid','comment_service','comment_worders',
            'comment_efficiency','comment_content',
         ]
      );

      // 验证我是否有权限操作
      $condition[] = [ 'user_id' , '=' , $user->id ];
      $condition[] = [ 'issueid' , '=' , $param[ 'issueid' ] ];
      $getWifiIssuesOneInfo = WifiIssuesDao::getWifiIssuesOneInfo ( $condition , [ 'issueid' , 'desc' ] , [ '*' ] );
      if ( !$getWifiIssuesOneInfo ) return JsonBuilder::Error ( '您没有权限评论此信息' );
      if ( $getWifiIssuesOneInfo['is_comment'] == 2 ) return JsonBuilder::Error ( '您已评价' );

      // 表单要插入的字段信息
      $param1 = self::getPostParamInfo ( $param , [
            'issueid' , 'comment_service' , 'comment_worders' , 'comment_efficiency' ,
            'comment_content',
         ]
      );

      // 更新的数据
      $param2[ 'user_id' ]   = $user->id; // 用户id
      $param2[ 'school_id' ] = $user->gradeUserOneInfo->school_id; // 学校
      $param2[ 'campus_id' ] = $user->gradeUserOneInfo->campus_id; // 校区

      // 验证数据是否重复提交
      $dataSign = sha1 ( $user->id . 'addApiWifiIssueCommentInfo' );

      if ( Cache::has ( $dataSign ) ) return JsonBuilder::Error ( '您提交太快了，先歇息一下。' );

      if ( WifiIssueCommentsDao::addOrUpdateWifiIssueCommentsInfo ( array_merge ( $param1 , $param2 ) ) )
      {
         // 生成重复提交签名
         Cache::put ( $dataSign , $dataSign , 10 );

         // 更新数据是评价
         WifiIssuesDao::addOrUpdateWifiIssuesInfo ( [ 'is_comment' => 2 ] , $param1[ 'issueid' ] );

         return JsonBuilder::Success ( '评论成功' );
      } else {

         return JsonBuilder::Error ( '操作失败,请稍后重试' );
      }
   }
}
