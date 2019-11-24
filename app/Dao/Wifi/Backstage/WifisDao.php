<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 18/10/19
 * Time: 11:47 AM
 */
namespace App\Dao\Wifi\Backstage;

use App\Models\Wifi\Backstage\Wifis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class WifisDao extends \App\Dao\Wifi\CommonDao
{
   /**
    * Func:  添加或更新数据
    * return 主键ID|false
    */
   public static function addOrUpdateWifisInfo( $data = [] , $contentid = null )
   {
      return Wifis::addOrUpdateWifisInfo ( $data , $contentid );
   }

   /**
    * Func:  删除数据
    * return true|false
    */
   public static function delWifisInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      return Wifis::delWifisInfo ( $condition , $saveFieldData , $isDelete );
   }

   /**
    * Func 获取单条数据
    * return array
    */
   public static function getWifisOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return Wifis::getWifisOneInfo ( $condition , $orderArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 获取多条数据
    * return array
    */
   public static function getWifisListInfo ( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return Wifis::getWifisListInfo ( $condition , $orderArr , $pageArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 统计数据
    * return Int
    */
   public static function getWifisStatistics ( $condition = [] , $field = null )
   {
      return Wifis::getWifisStatistics ( $condition , $field );
   }
}