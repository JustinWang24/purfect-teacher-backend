<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 18/10/19
 * Time: 11:47 AM
 */
namespace App\Dao\Wifi\Backstage;

use App\Models\Wifi\Backstage\WifiContents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class WifiContentsDao extends \App\Dao\Wifi\CommonDao
{
   /**
    * Func:  添加或更新数据
    * return 主键ID|false
    */
   public static function addOrUpdateWifiContentsInfo( $data = [] , $contentid = null )
   {
      return WifiContents::addOrUpdateWifiContentsInfo ( $data , $contentid );
   }

   /**
    * Func:  删除数据
    * return true|false
    */
   public static function delWifiContentsInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      return WifiContents::delWifiContentsInfo ( $condition , $saveFieldData , $isDelete );
   }

   /**
    * Func 获取单条数据
    * return array
    */
   public static function getWifiContentsOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiContents::getWifiContentsOneInfo ( $condition , $orderArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 获取多条数据
    * return array
    */
   public static function getWifiContentsListInfo ( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiContents::getWifiContentsListInfo ( $condition , $orderArr , $pageArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 统计数据
    * return Int
    */
   public static function getWifiContentsStatistics ( $condition = [] , $field = null )
   {
      return WifiContents::getWifiContentsStatistics ( $condition , $field );
   }
}