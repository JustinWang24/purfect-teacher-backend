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

class WifiIssueDisposes extends Model
{
   public function __construct(){}

   /**
    * Func:  添加或更新数据
    * @Param Array  数组  $data
    * @Param Int    主键  $disposeid
    * return 主键ID|false
    */
   public static function addOrUpdateWifiIssueDisposesInfo( $data = [] , $disposeid = null )
   {
      if ( empty( $data ) && ! is_array ( $data ) ) return false;
      if ( intval ( $disposeid ) )
      {
         $data = array_merge ( $data , [ 'updated_at' => date ( 'Y-m-d H:i:s' ) ] );
         if ( WifiIssueDisposes::where( 'disposeid' , '=' , $disposeid )->update ( $data ) )
         {
            return $disposeid;
         } else {
            return false;
         }
      }else{
         $data = array_merge ( $data , [ 'created_at' => date ( 'Y-m-d H:i:s' ) ] );
         if ( $disposeid = WifiIssueDisposes::insertGetId ( $data ) )
         {
            return $disposeid;
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
   public static function delWifiIssueDisposesInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      // 条件为空，不做处理
      if( !$condition ) return false;
      // 物理删除数据
      if( true == $isDelete )
      {
         $isStatus = WifiIssueDisposes::where($condition)->delete();
         return $isStatus ? true : false;
      }
      // 逻辑删除数据
      if( !$saveFieldData ) return false;
      if( false == $isDelete )
      {
         $saveData = array_merge ( $saveFieldData , [ 'updated_at' => date ( 'Y-m-d H:i:s' ) ] );
         if ( WifiIssueDisposes::where ( $condition )->update ( $saveData ) )
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
   public static function getWifiIssueDisposesOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiIssueDisposes::where ( $condition )->orderByArr ( $orderArr )->joinArr ( $joinArr )->first ( $fieldsArr );
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
   public static function getWifiIssueDisposesListInfo( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiIssueDisposes::where ( $condition )->orderByArr ( $orderArr )->joinArr ( $joinArr )
              ->paginate ( $pageArr[ 'limit' ] , $fieldsArr , 'page' , $pageArr[ 'page' ] );
   }

   /**
    * Func:  统计数据
    * @Param $condition array 查询条件
    * @Param $field string 获取的字段值
    * return Int
    */
   public static function getWifiIssueDisposesStatistics ( $condition = [] , $mode = 'count' , $field = null )
   {
      // 条件/ 排序必须唯一,必传参数
      if ( ! $condition ) return 0;
      
	  if ( in_array ( $mode , [ 'max' , 'min' , 'avg' , 'sum' ] ) && ! $field ) return 0;
	  
      if ( $mode == 'count' ) return WifiIssueDisposes::where ( $condition )->count ();
      if ( $mode == 'max' ) return WifiIssueDisposes::where ( $condition )->max ( $field );
      if ( $mode == 'min' ) return WifiIssueDisposes::where ( $condition )->min ( $field );
      if ( $mode == 'avg' ) return (float)WifiIssueDisposes::where ( $condition )->avg ( $field );
      if ( $mode == 'sum' ) return (float)WifiIssueDisposes::where ( $condition )->sum ( $field );
      return 0;
   }
}
