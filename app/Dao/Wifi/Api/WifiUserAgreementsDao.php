<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 18/10/19
 * Time: 11:47 AM
 */
namespace App\Dao\Wifi\Api;

use App\Dao\Wifi\CommonDao;
use App\Models\Wifi\Api\WifiUserAgreements;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class WifiUserAgreementsDao extends CommonDao
{
   /**
    * Func:  添加或更新数据
    * return 主键ID|false
    */
   public static function addOrUpdateWifiUserAgreementsInfo( $data = [] , $contentid = null )
   {
      return WifiUserAgreements::addOrUpdateWifiUserAgreementsInfo ( $data , $contentid );
   }

   /**
    * Func:  删除数据
    * return true|false
    */
   public static function delWifiUserAgreementsInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      return WifiUserAgreements::delWifiUserAgreementsInfo ( $condition , $saveFieldData , $isDelete );
   }

   /**
    * Func 获取单条数据
    * return array
    */
   public static function getWifiUserAgreementsOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiUserAgreements::getWifiUserAgreementsOneInfo ( $condition , $orderArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 获取多条数据
    * return array
    */
   public static function getWifiUserAgreementsListInfo ( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiUserAgreements::getWifiUserAgreementsListInfo ( $condition , $orderArr , $pageArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 统计数据
    * return Int
    */
   public static function getWifiUserAgreementsStatistics ( $condition = [] , $field = null )
   {
      return WifiUserAgreements::getWifiUserAgreementsStatistics ( $condition , $field );
   }
}