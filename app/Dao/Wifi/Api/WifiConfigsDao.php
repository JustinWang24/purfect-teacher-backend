<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 18/10/19
 * Time: 11:47 AM
 */
namespace App\Dao\Wifi\Api;

use App\Models\Wifi\Api\WifiConfigs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class WifiConfigsDao extends CommonDao
{
   /**
    * Func:  添加或更新数据
    * return 主键ID|false
    */
   public static function addOrUpdateWifiConfigsInfo( $data = [] , $contentid = null )
   {
      return WifiConfigs::addOrUpdateWifiConfigsInfo ( $data , $contentid );
   }

   /**
    * Func:  删除数据
    * return true|false
    */
   public static function delWifiConfigsInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      return WifiConfigs::delWifiConfigsInfo ( $condition , $saveFieldData , $isDelete );
   }

   /**
    * Func 获取单条数据
    * return array
    */
   public static function getWifiConfigsOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiConfigs::getWifiConfigsOneInfo ( $condition , $orderArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 获取多条数据
    * return array
    */
   public static function getWifiConfigsListInfo ( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiConfigs::getWifiConfigsListInfo ( $condition , $orderArr , $pageArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 统计数据
    * return Int
    */
   public static function getWifiConfigsStatistics ( $condition = [] , $field = null )
   {
      return WifiConfigs::getWifiConfigsStatistics ( $condition , $field );
   }
}