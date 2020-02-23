<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 18/10/19
 * Time: 11:47 AM
 */
namespace App\Dao\Wifi\Backstage;

use App\Models\Wifi\Backstage\WifiIssueComments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class WifiIssueCommentsDao extends CommonDao
{
   /**
    * Func:  添加或更新数据
    * return 主键ID|false
    */
   public static function addOrUpdateWifiIssueCommentsInfo( $data = [] , $contentid = null )
   {
      return WifiIssueComments::addOrUpdateWifiIssueCommentsInfo ( $data , $contentid );
   }

   /**
    * Func:  删除数据
    * return true|false
    */
   public static function delWifiIssueCommentsInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      return WifiIssueComments::delWifiIssueCommentsInfo ( $condition , $saveFieldData , $isDelete );
   }

   /**
    * Func 获取单条数据
    * return array
    */
   public static function getWifiIssueCommentsOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiIssueComments::getWifiIssueCommentsOneInfo ( $condition , $orderArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 获取多条数据
    * return array
    */
   public static function getWifiIssueCommentsListInfo ( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return WifiIssueComments::getWifiIssueCommentsListInfo ( $condition , $orderArr , $pageArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 统计数据
    * return Int
    */
   public static function getWifiIssueCommentsStatistics ( $condition = [] , $field = null )
   {
      return WifiIssueComments::getWifiIssueCommentsStatistics ( $condition , $field );
   }

}