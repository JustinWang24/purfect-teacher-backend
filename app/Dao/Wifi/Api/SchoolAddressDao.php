<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 18/10/19
 * Time: 11:47 AM
 */
namespace App\Dao\Wifi\Api;

use App\Models\Wifi\Api\SchoolAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class SchoolAddressDao extends CommonDao
{
   /**
    * Func:  添加或更新数据
    * return 主键ID|false
    */
   public static function addOrUpdateSchoolAddressInfo( $data = [] , $contentid = null )
   {
      return SchoolAddress::addOrUpdateSchoolAddressInfo ( $data , $contentid );
   }

   /**
    * Func:  删除数据
    * return true|false
    */
   public static function delSchoolAddressInfo( $condition = [] , $saveFieldData = [] , $isDelete = false )
   {
      return SchoolAddress::delSchoolAddressInfo ( $condition , $saveFieldData , $isDelete );
   }

   /**
    * Func 获取单条数据
    * return array
    */
   public static function getSchoolAddressOneInfo ( $condition = [] , $orderArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return SchoolAddress::getSchoolAddressOneInfo ( $condition , $orderArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 获取多条数据
    * return array
    */
   public static function getSchoolAddressListInfo ( $condition = [] , $orderArr = [] , $pageArr = [] , $fieldsArr = [] , $joinArr = [] )
   {
      return SchoolAddress::getSchoolAddressListInfo ( $condition , $orderArr , $pageArr , $fieldsArr , $joinArr );
   }

   /**
    * Func 统计数据
    * return Int
    */
   public static function getSchoolAddressStatistics ( $condition = [] , $field = null )
   {
      return SchoolAddress::getSchoolAddressStatistics ( $condition , $field );
   }
}