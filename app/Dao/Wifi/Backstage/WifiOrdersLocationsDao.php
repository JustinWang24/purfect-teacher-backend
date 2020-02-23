<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 18/10/19
 * Time: 11:47 AM
 */
namespace App\Dao\Wifi\Backstage;

use App\Models\Wifi\Backstage\WifiOrdersLocations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class WifiOrdersLocationsDao extends CommonDao
{
   /**
    * Func:  添加或更新数据
    * return 主键ID|false
    */
   public static function addOrUpdateWifiOrdersLocationsInfo( $data = [] , $contentid = null )
   {
      return WifiOrdersLocations::addOrUpdateWifiOrdersLocationsInfo ( $data , $contentid );
   }

   /**
    * Func:  删除数据
    * return true|false
    */
   public static function delWifiOrdersLocationsInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      return WifiOrdersLocations::delWifiOrdersLocationsInfo ( $condition , $saveFieldData , $isDelete );
   }

   /**
    * Func 获取单条数据
    * return array
    */
   public static function getWifiOrdersLocationsOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiOrdersLocations::getWifiOrdersLocationsOneInfo ( $condition , $orderArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 获取多条数据
    * return array
    */
   public static function getWifiOrdersLocationsListInfo ( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiOrdersLocations::getWifiOrdersLocationsListInfo ( $condition , $orderArr , $pageArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 统计数据
    * return Int
    */
   public static function getWifiOrdersLocationsStatistics ( $condition = [] , $field = null )
   {
      return WifiOrdersLocations::getWifiOrdersLocationsStatistics ( $condition , $field );
   }
}