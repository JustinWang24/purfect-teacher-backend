<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 18/10/19
 * Time: 11:47 AM
 */
namespace App\Dao\Wifi\Backstage;

use App\Models\Wifi\Backstage\WifiIssues;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class WifiIssuesDao extends CommonDao
{
   /**
    * Func:  添加或更新数据
    * return 主键ID|false
    */
   public static function addOrUpdateWifiIssuesInfo( $data = [] , $contentid = null )
   {
      return WifiIssues::addOrUpdateWifiIssuesInfo ( $data , $contentid );
   }

   /**
    * Func:  删除数据
    * return true|false
    */
   public static function delWifiIssuesInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      return WifiIssues::delWifiIssuesInfo ( $condition , $saveFieldData , $isDelete );
   }

   /**
    * Func 获取单条数据
    * return array
    */
   public static function getWifiIssuesOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiIssues::getWifiIssuesOneInfo ( $condition , $orderArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 获取多条数据
    * return array
    */
   public static function getWifiIssuesListInfo ( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiIssues::getWifiIssuesListInfo ( $condition , $orderArr , $pageArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 统计数据
    * return Int
    */
   public static function getWifiIssuesStatistics ( $condition = [] , $field = null )
   {
      return WifiIssues::getWifiIssuesStatistics ( $condition , $field );
   }
}