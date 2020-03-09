<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 21/10/19
 * Time: 9:05 AM
 */
namespace App\Dao\Wifi\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class CommonDao
{
   //支付方式 支付方式(0：免支付,1:微信,2:支付宝,3:兑换支付)
   public static $paymentidArr = [
      0 => '免支付' ,
      1 => '微信' ,
      2 => '支付宝',
      3 => '兑换支付',
   ];
   //---------------------------------------api定义的静态属性--------------------------------------
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
    public static function getBuildOneInfo ( $id = null )
    {
        if ( !intval ( $id ) ) return [];

        return DB::table ( 'buildings' )->where ( 'id' , $id )->first ();
    }

   /**
    * Func 获取学校地址信息
    * @param $id 地址id
    *            return array
    */
   public static function getRoomOneInfo ( $id = null )
   {
      if ( !intval ( $id ) ) return [];

      return DB::table ( 'rooms' )->where ( 'id' , $id )->first ();
   }

   //---------------------------------------manger定义的静态属性------------------------------------

   // wifi有线产品配置
   public static $manageWifiArr = [
      [ 'id' => 1 , 'name' => '日卡' , 'day' => 1 ] ,
      [ 'id' => 2 , 'name' => '1周' , 'day' => 7 ] ,
      [ 'id' => 3 , 'name' => '1个月' , 'day' => 30 ] ,
      [ 'id' => 4 , 'name' => '2个月' , 'day' => 60 ] ,
      [ 'id' => 5 , 'name' => '1季度' , 'day' => 90 ] ,
      [ 'id' => 6 , 'name' => '1学期' , 'day' => 150 ] ,
      [ 'id' => 7 , 'name' => '1年' , 'day' => 360 ] ,
      [ 'id' => 8 , 'name' => '2年' , 'day' => 730 ] ,
      [ 'id' => 9 , 'name' => '3年' , 'day' => 1095 ] ,
      ];

   // wifi充值状态
   public static $manageWifiStatusArr
      = [
      1 => '待支付' ,
      2 => '支付失败' ,
      3 => 'wifi充值中' ,
      4 => 'wifi充值成功' ,
      5 => 'wifi充值失败' ,
      6 => '退款成功' ,
   ];

   // 是否评价(1:未评价,2:已评价)
   public static $manageCommentArr = [
      1 => '未评价',
      2 => '已评价',
   ];

   // 状态,1:待处理,2:已接单，3:已完成,4:已取消,5:已关闭.
   public static $manageStatusArr = [
      1 => '待处理',
      2 => '处理中',
      3 => '已完成',
   ];

   // 报修类型
   public static $manageIssueTypesArr = [
      1 => 'APP类型',
      2 => '后台类型'
   ];
   //--------------------------------------public 公共方法——--------------------------------------

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

   public function get()
   {

   }

}
