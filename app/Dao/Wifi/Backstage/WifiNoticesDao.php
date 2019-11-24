<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 18/10/19
 * Time: 11:47 AM
 */
namespace App\Dao\Wifi\Backstage;

use App\Models\Wifi\Backstage\WifiNotices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class WifiNoticesDao extends \App\Dao\Wifi\CommonDao
{
   /**
    * Func:  添加或更新数据
    * return 主键ID|false
    */
   public static function addOrUpdateWifiNoticesInfo( $data = [] , $contentid = null )
   {
      return WifiNotices::addOrUpdateWifiNoticesInfo ( $data , $contentid );
   }

   /**
    * Func:  删除数据
    * return true|false
    */
   public static function delWifiNoticesInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      return WifiNotices::delWifiNoticesInfo ( $condition , $saveFieldData , $isDelete );
   }

   /**
    * Func 获取单条数据
    * return array
    */
   public static function getWifiNoticesOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiNotices::getWifiNoticesOneInfo ( $condition , $orderArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 获取多条数据
    * return array
    */
   public static function getWifiNoticesListInfo ( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiNotices::getWifiNoticesListInfo ( $condition , $orderArr , $pageArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 统计数据
    * return Int
    */
   public static function getWifiNoticesStatistics ( $condition = [] , $field = null )
   {
      return WifiNotices::getWifiNoticesStatistics ( $condition , $field );
   }
}