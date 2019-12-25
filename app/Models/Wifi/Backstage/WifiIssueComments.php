<?php
   /**
    * Created by PhpStorm.
    * User: zhang.kui
    * Date: 19/11/19
    * Time: 11:33 AM
    */
namespace App\Models\Wifi\Backstage;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class WifiIssueComments extends Model
{
   public function __construct(){}

   /**
    * Func:  添加或更新数据
    * @Param Array  数组  $data
    * @Param Int    主键  $commentid
    * return 主键ID|false
    */
   public static function addOrUpdateWifiIssueCommentsInfo( $data = [] , $commentid = null )
   {
      if ( empty( $data ) && ! is_array ( $data ) ) return false;
      if ( intval ( $commentid ) )
      {
         $data = array_merge ( $data , [ 'updated_at' => date ( 'Y-m-d H:i:s' ) ] );
         if ( WifiIssueComments::where( 'commentid' , '=' , $commentid )->update ( $data ) )
         {
            return $commentid;
         } else {
            return false;
         }
      }else{
         $data = array_merge ( $data , [ 'created_at' => date ( 'Y-m-d H:i:s' ) ] );
         if ( $commentid = WifiIssueComments::insertGetId ( $data ) )
         {
            return $commentid;
         } else {
            return false;
         }
      }
   }

   /**
    * Func:  删除数据
    * @Param $condition array       查询条件
    * @Param $saveFieldData array   逻辑更新
    * return true|false
    */
   public static function delWifiIssueCommentsInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      // 条件为空，不做处理
      if( !$condition ) return false;
      // 物理删除数据
      if( true == $isDelete )
      {
         $isStatus = WifiIssueComments::where($condition)->delete();
         return $isStatus ? true : false;
      }
      // 逻辑删除数据
      if( !$saveFieldData ) return false;
      if( false == $isDelete )
      {
         $saveData = array_merge ( $saveFieldData , [ 'updated_at' => date ( 'Y-m-d H:i:s' ) ] );
         if ( WifiIssueComments::where ( $condition )->update ( $saveData ) )
         {
            return true;
         } else {
            return false;
         }
      }
   }

   /**
    * Func:  获取单条数据
    * @Param $condition array 查询条件
    * @Param $orderArr array 排序
    * @Param $fieldsArr array 获取的字段信息
    * @Param $joinArr array 需要连接json的数据表
    * return array
    */
   public static function getWifiIssueCommentsOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      $joinCount = count($joinArr);
      if($joinCount == 1) // 一张表
      {
         return WifiIssueComments::where ( $condition )
                 ->orderBy ( $orderArr[0],$orderArr[1] )
                 ->join($joinArr[0][0],$joinArr[0][1],$joinArr[0][2],$joinArr[0][3],isset($joinArr[0][4])?$joinArr[0][4]:'inner')
                 ->first ( $fieldsArr );;
      }
      if($joinCount == 2) // 二张表
      {
         return WifiIssueComments::where ( $condition )
                  ->orderBy ( $orderArr[0],$orderArr[1] )
                  ->join($joinArr[0][0],$joinArr[0][1],$joinArr[0][2],$joinArr[0][3],isset($joinArr[0][4])?$joinArr[0][4]:'inner')
                  ->join($joinArr[1][0],$joinArr[1][1],$joinArr[1][2],$joinArr[1][3],isset($joinArr[1][4])?$joinArr[1][4]:'inner')
                  ->first ( $fieldsArr );;
      }
      return WifiIssueComments::where ( $condition )->orderBy ( $orderArr[0],$orderArr[1] )->first ( $fieldsArr );
   }

   /**
    * Func:  获取多条数据
    * @Param $condition array 查询条件
    * @Param $orderArr array 排序字段
    * @Param $pageArr  array 分页数据
    * @Param $fieldsArr  array 获取的字段信息
    * @Param $joinArr  array 需要连接的数据表
    * return array
    */
   public static function getWifiIssueCommentsListInfo( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      $joinCount = count($joinArr);
      if($joinCount == 1) // 一张表
      {
         return WifiIssueComments::where ( $condition )
                  ->join($joinArr[0][0],$joinArr[0][1],$joinArr[0][2],$joinArr[0][3],isset($joinArr[0][4])?$joinArr[0][4]:'inner')
                  ->orderBy ( $orderArr[0],$orderArr[1] )
                  ->paginate ( $pageArr[ 'limit' ] , $fieldsArr , 'page' , $pageArr[ 'page' ] );
      }
      if($joinCount == 2) // 二张表
      {
         return WifiIssueComments::where ( $condition )
                 ->join($joinArr[0][0],$joinArr[0][1],$joinArr[0][2],$joinArr[0][3],isset($joinArr[0][4])?$joinArr[0][4]:'inner')
                 ->join($joinArr[1][0],$joinArr[1][1],$joinArr[1][2],$joinArr[1][3],isset($joinArr[1][4])?$joinArr[1][4]:'inner')
                 ->orderBy ( $orderArr[0],$orderArr[1] )
                 ->paginate ( $pageArr[ 'limit' ] , $fieldsArr , 'page' , $pageArr[ 'page' ] );
      }
      return WifiIssueComments::where ( $condition )->orderBy ( $orderArr[0],$orderArr[1] )
              ->paginate ( $pageArr[ 'limit' ] , $fieldsArr , 'page' , $pageArr[ 'page' ] );
   }

   /**
    * Func:  统计数据
    * @Param $condition array 查询条件
    * @Param $field string 获取的字段值
    * return Int
    */
   public static function getWifiIssueCommentsStatistics ( $condition = [] , $mode = 'count' , $field = null )
   {
      // 条件/ 排序必须唯一,必传参数
      if ( ! $condition ) return 0;
      
	  if ( in_array ( $mode , [ 'max' , 'min' , 'avg' , 'sum' ] ) && ! $field ) return 0;
	  
      if ( $mode == 'count' ) return WifiIssueComments::where ( $condition )->count ();
      if ( $mode == 'max' ) return WifiIssueComments::where ( $condition )->max ( $field );
      if ( $mode == 'min' ) return WifiIssueComments::where ( $condition )->min ( $field );
      if ( $mode == 'avg' ) return (float)WifiIssueComments::where ( $condition )->avg ( $field );
      if ( $mode == 'sum' ) return (float)WifiIssueComments::where ( $condition )->sum ( $field );
      return 0;
   }
}
