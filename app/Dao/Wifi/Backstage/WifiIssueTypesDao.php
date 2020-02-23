<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 18/10/19
 * Time: 11:47 AM
 */
namespace App\Dao\Wifi\Backstage;

use App\Models\Wifi\Backstage\WifiIssueTypes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class WifiIssueTypesDao extends CommonDao
{
   /**
    * Func:  添加或更新数据
    * return 主键ID|false
    */
   public static function addOrUpdateWifiIssueTypesInfo( $data = [] , $contentid = null )
   {
      return WifiIssueTypes::addOrUpdateWifiIssueTypesInfo ( $data , $contentid );
   }

   /**
    * Func:  删除数据
    * return true|false
    */
   public static function delWifiIssueTypesInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      return WifiIssueTypes::delWifiIssueTypesInfo ( $condition , $saveFieldData , $isDelete );
   }

   /**
    * Func 获取单条数据
    * return array
    */
   public static function getWifiIssueTypesOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiIssueTypes::getWifiIssueTypesOneInfo ( $condition , $orderArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 获取多条数据
    * return array
    */
   public static function getWifiIssueTypesListInfo ( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiIssueTypes::getWifiIssueTypesListInfo ( $condition , $orderArr , $pageArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 统计数据
    * return Int
    */
   public static function getWifiIssueTypesStatistics ( $condition = [] , $field = null )
   {
      return WifiIssueTypes::getWifiIssueTypesStatistics ( $condition , $field );
   }
}