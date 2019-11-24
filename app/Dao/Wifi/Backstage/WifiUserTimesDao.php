<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 18/10/19
 * Time: 11:47 AM
 */
namespace App\Dao\Wifi\Backstage;

use App\Models\Wifi\Backstage\WifiUserTimes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class WifiUserTimesDao extends \App\Dao\Wifi\CommonDao
{
   /**
    * Func:  添加或更新数据
    * return 主键ID|false
    */
   public static function addOrUpdateWifiUserTimesInfo( $data = [] , $contentid = null )
   {
      return WifiUserTimes::addOrUpdateWifiUserTimesInfo ( $data , $contentid );
   }

   /**
    * Func:  删除数据
    * return true|false
    */
   public static function delWifiUserTimesInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      return WifiUserTimes::delWifiUserTimesInfo ( $condition , $saveFieldData , $isDelete );
   }

   /**
    * Func 获取单条数据
    * return array
    */
   public static function getWifiUserTimesOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiUserTimes::getWifiUserTimesOneInfo ( $condition , $orderArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 获取多条数据
    * return array
    */
   public static function getWifiUserTimesListInfo ( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiUserTimes::getWifiUserTimesListInfo ( $condition , $orderArr , $pageArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 统计数据
    * return Int
    */
   public static function getWifiUserTimesStatistics ( $condition = [] , $field = null )
   {
      return WifiUserTimes::getWifiUserTimesStatistics ( $condition , $field );
   }
}