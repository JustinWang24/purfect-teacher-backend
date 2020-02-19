<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 18/10/19
 * Time: 11:47 AM
 */
namespace App\Dao\Wifi\Api;

use App\Dao\Wifi\CommonDao;
use App\Models\Wifi\Api\WifiOrders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class WifiOrdersDao extends CommonDao
{
   /**
    * Func:  添加或更新数据
    * return 主键ID|false
    */
   public static function addOrUpdateWifiOrdersInfo( $data = [] , $contentid = null )
   {
      return WifiOrders::addOrUpdateWifiOrdersInfo ( $data , $contentid );
   }

   /**
    * Func:  删除数据
    * return true|false
    */
   public static function delWifiOrdersInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      return WifiOrders::delWifiOrdersInfo ( $condition , $saveFieldData , $isDelete );
   }

   /**
    * Func 获取单条数据
    * return array
    */
   public static function getWifiOrdersOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiOrders::getWifiOrdersOneInfo ( $condition , $orderArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 获取多条数据
    * return array
    */
   public static function getWifiOrdersListInfo ( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiOrders::getWifiOrdersListInfo ( $condition , $orderArr , $pageArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 统计数据
    * return Int
    */
   public static function getWifiOrdersStatistics ( $condition = [] , $field = null )
   {
      return WifiOrders::getWifiOrdersStatistics ( $condition , $field );
   }
}