<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 19/11/19
 * Time: 11:33 AM
 */
namespace App\Http\Controllers\Api\Wifi;

use Psy\Util\Json;
use App\Utils\JsonBuilder;
use App\Http\Requests\Api\Wifi\WifiCableRequest;
use App\Http\Controllers\Controller;

use App\Dao\Wifi\Api\WifiConfigsDao;
use App\Dao\Wifi\Api\SchoolAddressDao; // 学校地址
use App\Dao\Wifi\Api\WifisDao; // wifi
use App\Dao\Wifi\Api\RoomsDao; // 房间
use App\Dao\Wifi\Api\WifiContentsDao; // 常用须知
use App\Dao\Wifi\Api\WifiUserTimesDao; // 用户wifi和套餐时长
use App\Dao\Wifi\Api\WifiOrdersDao; // wifi订单
use App\Dao\Wifi\Api\WifiOrdersLocationsDao; // 用户有线开通记录表



class WifiCableController extends Controller
{
   /**
    * Func 已开通有线
    * @param Request $request
    * @return Json
    */
   public function index_cable(WifiCableRequest $request)
   {
      $user = $request->user ();

      // 获取我的wifi有线使用结束时间
      $condition[] = [ 'user_id' , '=' , $user->id ];
      $condition[] = [ 'status' , '=' , 1 ];
      $getWifiUserTimesOneInfo = WifiUserTimesDao::getWifiUserTimesOneInfo (
         $condition , [ [ 'timesid' , 'desc' ] ] , [ 'timesid' , 'user_wifi_time' ]
      );

      // 用户有线有效期时间
      $infos['user_wifi_time'] = 0;
      if ( $getWifiUserTimesOneInfo && strtotime ( $getWifiUserTimesOneInfo->user_wifi_time ) > time () )
      {
         $infos['user_wifi_time'] = strtotime ($getWifiUserTimesOneInfo->user_wifi_time);
      }

      // 获取服务器配置端口
      $condition1[] = [ 'campus_id' , '=' , $user->gradeUser->campus_id ];
      $condition1[] = [ 'status' , '=' , 1 ];

      $getWifiConfigsOneInfo = WifiConfigsDao::getWifiConfigsOneInfo (
         $condition1 , [ [ 'configid' , 'desc' ] ] , [ 'config_port_count' ]
      );

      $infos['config_port_count'] = 0;
      if ( $getWifiConfigsOneInfo && $getWifiConfigsOneInfo->config_port_count > 0 )
      {
         $infos['config_port_count'] = $getWifiConfigsOneInfo->config_port_count;
      }

      // 获取之前已开通的有线
      $condition2[] = [ 'user_id' , '=' , $user->id ];
      $condition2[] = [ 'status' , '=' , 1 ];
      $fieldArr1    = [
         'addressoneid' , 'addresstwoid' , 'addressthreeid' , 'addressfourid' ,
         'addressoneid_name' , 'addresstwoid_name' , 'addressthreeid_name' ,
         'addressfourid_name','address_port'
      ];
      $getWifiOrdersLocationsOneInfo = WifiOrdersLocationsDao::getWifiOrdersLocationsOneInfo (
         $condition2 , [ [ 'locationid' , 'desc' ] ] , $fieldArr1
      );
      $infos[ 'address_info' ] = $getWifiOrdersLocationsOneInfo ? $getWifiOrdersLocationsOneInfo->toArray () : [];

      return JsonBuilder::Success ( $infos ,'已开通有线信息');
   }

   /**
    * Func 宿舍楼地址列表
    * @param WifiIssueRequest $request
    * @return json
    */
   public function list_category_info ( WifiCableRequest $request )
   {
      $user = $request->user ();
      // TODO......获取地址有问题........
      // 查询条件
      $condition[] = [ 'campus_id' , '=' , $user->gradeUser->campus_id];
      $condition[] = [ 'type' , '=' , 5 ]; // 类型 1教室，2:智慧教室,3:会议室,4:教师办公室,5:学生宿舍

      // 获取的字段信息
      $fieldArr = [ 'id as addresstwoid' , 'building_id as addressoneid' , 'name' ];
      $getSchoolAddressListInfo = RoomsDao::getRoomsListInfo (
         $condition , [ [ 'id' , 'asc' ] ] , [ 'page' => 1 , 'limit' => 800 ] , $fieldArr
      );

      $infos = $getSchoolAddressListInfo ? $getSchoolAddressListInfo->toArray ()[ 'data' ] : [];

      $infos1 = SchoolAddressDao::cateTree( $infos , 'addresstwoid' , 'addressoneid' );
;
      return JsonBuilder::Success ( $infos ,'宿舍楼地址列表');
   }

   /**
    * Func 已经开通的端口
    * @param WifiCableRequest $request
    * @return json
    */
   public function list_open_cable_info ( WifiCableRequest $request )
   {
      $user  = $request->user ();

      $param = $request->only ( [ 'addressid' ] );
      if ( !intval ( $param[ 'addressid' ] ) )
      {
         return JsonBuilder::Error ( '参数错误' );
      }

      // 查询条件
      $condition[] = [ 'addresstwoid' , '=' , $param[ 'addressid' ] ];
      $condition[] = [ 'status' , '=' , 1 ]; // 状态(0:未开通,1:已开通)

      // 获取的字段信息
      $getWifiOrdersLocationsListInfo = WifiOrdersLocationsDao::getWifiOrdersLocationsListInfo (
         $condition , [ [ 'address_port' , 'asc' ] ] , [ 'page' => 1 , 'limit' => 10 ] , [ 'address_port' ]
      );
      $infos = $getWifiOrdersLocationsListInfo ? $getWifiOrdersLocationsListInfo->toArray ()[ 'data' ] : [];

      return JsonBuilder::Success ( $infos ,'已开通的端口');
   }

   /**
    * Func 开通有线
    * @param WifiIssueRequest $request
    * @return json
    */
   public function add_cable_info ( WifiCableRequest $request )
   {
      $user  = $request->user ();

      $param = $request->only (
         [
            'address_port' , 'addressoneid' , 'addressoneid_name' ,
            'addresstwoid' , 'addresstwoid_name',
         ]
      );

      // 获取我的wifi时长
      $condition[] = [ 'user_id' , '=' , $user->id ];
      $condition[] = [ 'status' , '=' , 1 ];

      $getWifiUserTimesOneInfo = WifiUserTimesDao::getWifiUserTimesOneInfo (
         $condition , [ [ 'timesid' , 'desc' ] ] , [ 'timesid' , 'user_wifi_time','user_cable_etime' ]
      );

      // 未开通wifi无线不能申请有线
      if ( empty( $getWifiUserTimesOneInfo ) || (strtotime ( $getWifiUserTimesOneInfo->user_wifi_time ) < time ()))
      {
         return JsonBuilder::Error ( '请先充值后再申请开通有线' );
      }

      // 如果已经开通过不需要在开通
      if ( (strtotime ( $getWifiUserTimesOneInfo->user_cable_etime ) > time ()))
      {
         return JsonBuilder::Error ( '您已经开通过，不需要再次开通' );
      }

      // 验证wifi有线是否存在
      $condition1[]    = [ 'campus_id' , '=' , $user->gradeUser->campus_id ];
      $condition1[]    = [ 'mode' , '=' , 2 ];
      $condition1[]    = [ 'status' , '=' , 1 ];
      $getWifisOneInfo = WifisDao::getWifisOneInfo ( $condition1 , [ [ 'wifiid' , 'desc' ] ] , [ '*' ] );
      if ( empty( $getWifisOneInfo ) ) return JsonBuilder::Error ( '产品不存在' );

      // 表单要插入的字段信息
      $param1 = self::getPostParamInfo ( $param , [
            'addressoneid' , 'addressoneid_name' , 'addresstwoid' , 'addresstwoid_name' ,
            'addressthreeid' , 'addressthreeid_name' , 'addressfourid' , 'addressfourid_name'
         ]
      );

      // 添加订单信息
      $addData[ 'pay_time' ]         = date('Y-m-d H:i:s');
      $addData[ 'succeed_time' ]     = date('Y-m-d H:i:s');
      $addData[ 'paymentid' ]        = 4; // 支付方式(1:微信,2:支付宝,3:钱包支付,4:免支付)
      $addData[ 'payment_name' ]     = '免支付';
      $addData[ 'type' ]             = 1; // 类型(1:充值,2:赠送)
      $addData[ 'mode' ]             = 2; // 类型(1:无线，2：有线)
      $addData[ 'trade_sn' ]         = date ( 'YmdHis' ); // 编号
      $addData[ 'user_id' ]          = $user->id; // 用户id
      $addData[ 'school_id' ]        = $user->gradeUser->school_id; // 学校
      $addData[ 'campus_id' ]        = $user->gradeUser->campus_id; // 校区
      $addData[ 'wifi_id' ]          = $getWifisOneInfo->wifiid; // wifiid
      $addData[ 'wifi_type' ]        = $getWifisOneInfo->wifi_type; // wifi类型
      $addData[ 'wifi_name' ]        = $getWifisOneInfo->wifi_name; // wifi名称
      $addData[ 'order_days' ]       = 1; // 购买天数 TODO.....
      $addData[ 'order_number' ]     = 1; // 购买数量
      $addData[ 'order_unitprice' ]  = $getWifisOneInfo->wifi_price; // 购买数量
      $addData[ 'order_totalprice' ] = $getWifisOneInfo->wifi_price * $addData[ 'order_number' ]; // 总价格

      if ( $orderid = WifiOrdersDao::addOrUpdateWifiOrdersInfo ( $addData ) )
      {
         // 添加wifi有线信息
         $addData1[ 'status' ]    = 1;
         $addData1[ 'orderid' ]   = $orderid;
         $addData1[ 'user_id' ]   = $user->id; // 用户id
         $addData1[ 'school_id' ] = $user->gradeUser->school_id; // 学校
         $addData1[ 'campus_id' ] = $user->gradeUser->campus_id; // 校区
         WifiOrdersLocationsDao::addOrUpdateWifiOrdersLocationsInfo ( array_merge ( $addData1 , $param1 ) );

         // 更新有线时长
         $saveData[ 'user_cable_etime' ] = date ( 'Y-m-d H:i:s' , time () + ( 365 * 86400 ) );
         WifiUserTimesDao::addOrUpdateWifiUserTimesInfo ( $saveData , $getWifiUserTimesOneInfo[ 'timesid' ] );

         return JsonBuilder::Success ( '操作成功' );

      } else {

         return JsonBuilder::Error ( '操作失败,请稍后重试' );

      }
   }

}
