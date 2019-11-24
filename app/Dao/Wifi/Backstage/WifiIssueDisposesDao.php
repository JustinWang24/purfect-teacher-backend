<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 18/10/19
 * Time: 11:47 AM
 */
namespace App\Dao\Wifi\Backstage;

use App\Models\Wifi\Backstage\WifiIssueDisposes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class WifiIssueDisposesDao extends \App\Dao\Wifi\CommonDao
{
   /**
    * Func:  添加或更新数据
    * return 主键ID|false
    */
   public static function addOrUpdateWifiIssueDisposesInfo( $data = [] , $contentid = null )
   {
      return WifiIssueDisposes::addOrUpdateWifiIssueDisposesInfo ( $data , $contentid );
   }

   /**
    * Func:  删除数据
    * return true|false
    */
   public static function delWifiIssueDisposesInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      return WifiIssueDisposes::delWifiIssueDisposesInfo ( $condition , $saveFieldData , $isDelete );
   }

   /**
    * Func 获取单条数据
    * return array
    */
   public static function getWifiIssueDisposesOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiIssueDisposes::getWifiIssueDisposesOneInfo ( $condition , $orderArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 获取多条数据
    * return array
    */
   public static function getWifiIssueDisposesListInfo ( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiIssueDisposes::getWifiIssueDisposesListInfo ( $condition , $orderArr , $pageArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 统计数据
    * return Int
    */
   public static function getWifiIssueDisposesStatistics ( $condition = [] , $field = null )
   {
      return WifiIssueDisposes::getWifiIssueDisposesStatistics ( $condition , $field );
   }

}