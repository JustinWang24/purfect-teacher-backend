<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 21/10/19
 * Time: 9:05 AM
 */

namespace App\Dao\Wifi;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class CommonDao
{
   // Wifi通知状态 状态(0:关闭,1:开启)
   public static $wifiContentsStatusArr = [
      0 => '关闭' ,
      1 => '开启' ,
   ];
   // Wifi通知类型 (1:校园网通知,2:用网须知,3:充值通知,4:套餐说明,5:网络协议)
   public static $wifiContentsTypeArr = [
      1 => '校园网通知' ,
      2 => '用网须知' ,
      3 => '充值通知' ,
      4 => '套餐说明' ,
      5 => '网络协议' ,
   ];

    // wifi报修是否评价(1:未评价,2:已评价)
    public static $commentArr = [
        1 => '未评价',
        2 => '已评价',
    ];

    // wifi报修状态,1:待处理,2:已接单，3:已完成,4:已取消,5:已关闭.
    public static $statusArr = [
        1 => '待处理',
        2 => '处理中',
        3 => '已完成',
        4 => '已取消',
        5 => '已关闭',
    ];

   /**
    * Func 获取学校地址信息
    * @param $id 地址id
    *            return array
    */
   public static function getSchoolAddressOneInfo ( $id = null )
   {
      if ( !intval ( $id ) ) return [];

      return DB::table ( 'school_address' )->where ( 'id' , $id )->first ();
   }

   /**
    * Func 树接口
    *
    * @param $list
    *
    * @return array
    */
   public static function cateTree( $list , $idName = 'id' , $pidName = 'pid' )
   {
      $items = [ ];
      foreach ( $list as $k => $v )
      {
         $items[ $v[ $idName ] ] = $v;
      }
      $tree = [ ];
      foreach ( $items as $item )
      {
         if ( isset( $items[ $item[ $pidName ] ] ) )
         {
            $items[ $item[ $pidName ] ][ 'child' ][] = &$items[ $item[ $idName ] ];
         } else {
            $tree[] = &$items[ $item[ $idName ] ];
         }
      }
      return $tree;
   }
}