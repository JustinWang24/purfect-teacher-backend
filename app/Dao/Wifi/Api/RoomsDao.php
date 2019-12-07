<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 18/10/19
 * Time: 11:47 AM
 */
namespace App\Dao\Wifi\Api;

use App\Models\Wifi\Api\Rooms;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class RoomsDao extends \App\Dao\Wifi\CommonDao
{
   /**
    * Func:  添加或更新数据
    * return 主键ID|false
    */
   public static function addOrUpdateRoomsInfo( $data = [] , $contentid = null )
   {
      return Rooms::addOrUpdateRoomsInfo ( $data , $contentid );
   }

   /**
    * Func:  删除数据
    * return true|false
    */
   public static function delRoomsInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      return Rooms::delRoomsInfo ( $condition , $saveFieldData , $isDelete );
   }

   /**
    * Func 获取单条数据
    * return array
    */
   public static function getRoomsOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return Rooms::getRoomsOneInfo ( $condition , $orderArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 获取多条数据
    * return array
    */
   public static function getRoomsListInfo ( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return Rooms::getRoomsListInfo ( $condition , $orderArr , $pageArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 统计数据
    * return Int
    */
   public static function getRoomsStatistics ( $condition = [] , $field = null )
   {
      return Rooms::getRoomsStatistics ( $condition , $field );
   }
}