<?php
   /**
    * Created by PhpStorm.
    * User: zhang.kui
    * Date: 19/11/19
    * Time: 11:33 AM
    */
namespace App\Models\Wifi\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class WifiUserAgreements extends Model
{
   public function __construct(){}

   /**
    * Func:  添加或更新数据
    * @Param Array  数组  $data
    * @Param Int    主键  $agreementid
    * return 主键ID|false
    */
   public static function addOrUpdateWifiUserAgreementsInfo( $data = [] , $agreementid = null )
   {
      if ( empty( $data ) && ! is_array ( $data ) ) return false;
      if ( intval ( $agreementid ) )
      {
         $data = array_merge ( $data , [ 'updated_at' => date ( 'Y-m-d H:i:s' ) ] );
         if ( WifiUserAgreements::where( 'agreementid' , '=' , $agreementid )->update ( $data ) )
         {
            return $agreementid;
         } else {
            return false;
         }
      }else{
         $data = array_merge ( $data , [ 'created_at' => date ( 'Y-m-d H:i:s' ) ] );
         if ( $agreementid = WifiUserAgreements::insertGetId ( $data ) )
         {
            return $agreementid;
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
   public static function delWifiUserAgreementsInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      // 条件为空，不做处理
      if( !$condition ) return false;
      // 物理删除数据
      if( true == $isDelete )
      {
         $isStatus = WifiUserAgreements::where($condition)->delete();
         return $isStatus ? true : false;
      }
      // 逻辑删除数据
      if( !$saveFieldData ) return false;
      if( false == $isDelete )
      {
         $saveData = array_merge ( $saveFieldData , [ 'updated_at' => date ( 'Y-m-d H:i:s' ) ] );
         if ( WifiUserAgreements::where ( $condition )->update ( $saveData ) )
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
   public static function getWifiUserAgreementsOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      $joinCount = count($joinArr);
      if($joinCount == 1) // 一张表
      {
         return WifiUserAgreements::where ( $condition )
                 ->orderBy ( $orderArr[0],$orderArr[1] )
                 ->join($joinArr[0][0],$joinArr[0][1],$joinArr[0][2],$joinArr[0][3],isset($joinArr[0][4])?$joinArr[0][4]:'inner')
                 ->first ( $fieldsArr );;
      }
      if($joinCount == 2) // 二张表
      {
         return WifiUserAgreements::where ( $condition )
                  ->orderBy ( $orderArr[0],$orderArr[1] )
                  ->join($joinArr[0][0],$joinArr[0][1],$joinArr[0][2],$joinArr[0][3],isset($joinArr[0][4])?$joinArr[0][4]:'inner')
                  ->join($joinArr[1][0],$joinArr[1][1],$joinArr[1][2],$joinArr[1][3],isset($joinArr[1][4])?$joinArr[1][4]:'inner')
                  ->first ( $fieldsArr );;
      }
      return WifiUserAgreements::where ( $condition )->orderBy ( $orderArr[0],$orderArr[1] )->first ( $fieldsArr );
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
   public static function getWifiUserAgreementsListInfo( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      $joinCount = count($joinArr);
      if($joinCount == 1) // 一张表
      {
         return WifiUserAgreements::where ( $condition )
                  ->join($joinArr[0][0],$joinArr[0][1],$joinArr[0][2],$joinArr[0][3],isset($joinArr[0][4])?$joinArr[0][4]:'inner')
                  ->orderBy ( $orderArr[0],$orderArr[1] )
                  ->paginate ( $pageArr[ 'limit' ] , $fieldsArr , 'page' , $pageArr[ 'page' ] );
      }
      if($joinCount == 2) // 二张表
      {
         return WifiUserAgreements::where ( $condition )
                 ->join($joinArr[0][0],$joinArr[0][1],$joinArr[0][2],$joinArr[0][3],isset($joinArr[0][4])?$joinArr[0][4]:'inner')
                 ->join($joinArr[1][0],$joinArr[1][1],$joinArr[1][2],$joinArr[1][3],isset($joinArr[1][4])?$joinArr[1][4]:'inner')
                 ->orderBy ( $orderArr[0],$orderArr[1] )
                 ->paginate ( $pageArr[ 'limit' ] , $fieldsArr , 'page' , $pageArr[ 'page' ] );
      }
      return WifiUserAgreements::where ( $condition )->orderBy ( $orderArr[0],$orderArr[1] )
              ->paginate ( $pageArr[ 'limit' ] , $fieldsArr , 'page' , $pageArr[ 'page' ] );
   }

   /**
    * Func:  统计数据
    * @Param $condition array 查询条件
    * @Param $field string 获取的字段值
    * return Int
    */
   public static function getWifiUserAgreementsStatistics ( $condition = [] , $mode = 'count' , $field = null )
   {
      // 条件/ 排序必须唯一,必传参数
      if ( ! $condition ) return 0;
      
	  if ( in_array ( $mode , [ 'max' , 'min' , 'avg' , 'sum' ] ) && ! $field ) return 0;

      if ( $mode == 'count' ) return WifiUserAgreements::where ( $condition )->count ();
      if ( $mode == 'max' ) return WifiUserAgreements::where ( $condition )->max ( $field );
      if ( $mode == 'min' ) return WifiUserAgreements::where ( $condition )->min ( $field );
      if ( $mode == 'avg' ) return (float)WifiUserAgreements::where ( $condition )->avg ( $field );
      if ( $mode == 'sum' ) return (float)WifiUserAgreements::where ( $condition )->sum ( $field );
      return 0;
   }
}
