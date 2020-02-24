<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 18/10/19
 * Time: 11:47 AM
 */
namespace App\Dao\Wifi\Backstage;

use App\Models\Wifi\Backstage\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class UsersDao extends CommonDao
{
   /**
    * Func:  添加或更新数据
    * return 主键ID|false
    */
   public static function addOrUpdateUsersInfo( $data = [] , $contentid = null )
   {
      return Users::addOrUpdateUsersInfo ( $data , $contentid );
   }

   /**
    * Func:  删除数据
    * return true|false
    */
   public static function delUsersInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      return Users::delUsersInfo ( $condition , $saveFieldData , $isDelete );
   }

   /**
    * Func 获取单条数据
    * return array
    */
   public static function getUsersOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return Users::getUsersOneInfo ( $condition , $orderArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 获取多条数据
    * return array
    */
   public static function getUsersListInfo ( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return Users::getUsersListInfo ( $condition , $orderArr , $pageArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 统计数据
    * return Int
    */
   public static function getUsersStatistics ( $condition = [] , $field = null )
   {
      return Users::getUsersStatistics ( $condition , $field );
   }
}