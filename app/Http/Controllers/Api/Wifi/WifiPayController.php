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
use App\Http\Requests\Api\Wifi\WifiPayRequest;
use App\Http\Controllers\Controller;

use App\Dao\Wifi\Api\WifisDao; // wifi 产品
use App\Dao\Wifi\Api\WifiContentsDao; // 常用须知
use App\Dao\Wifi\Api\WifiUserTimesDao; // 用户wifi和套餐时长
use App\Dao\Wifi\Api\WifiOrdersDao; // wifi订单

use App\Dao\Wifi\Api\WifiConfigsDao; // wifi 配置
use App\Dao\Wifi\Api\WifiIssuesDao; // 报修单
use App\Dao\Wifi\Api\WifiUserAgreementsDao; // wifi协议

use App\BusinessLogic\WifiInterface\Factory;

class WifiPayController extends Controller
{
   /**
    * Func 充值上网
    * @param Request $request
    * @return Json
    */
   public function index_recharge(WifiPayRequest $request)
   {
      $user = $request->user ();

      if ( ! intval ( $user->gradeUser->campus_id ) )
      {
         return JsonBuilder::Error ( '参数错误' );
      }

      // wifi产品列表
      $condition[] = [ 'campus_id' , '=' , $user->gradeUser->campus_id ];
      $condition[] = [ 'mode' , '=' , 2 ];
      $condition[] = [ 'status' , '=' , 1 ];

      $infos[ 'wifiList' ] = WifisDao::getWifisListInfo (

         $condition , [ 'wifi_sort' , 'asc' ] , [ 'page' => 1 , 'limit' => 10 ] ,

         [ 'wifiid' , 'wifi_name' , 'wifi_oprice' , 'wifi_price' ]

      )->toArray ()[ 'data' ];

      // 获取通知信息
      $condition1[] = [ 'campus_id' , '=' , $user->gradeUser->campus_id ];
      $condition1[] = [ 'typeid' , '=' , 3 ];
      $condition1[] = [ 'status' , '=' , 1 ];

      $getWifiContentsOneInfo = WifiContentsDao::getWifiContentsOneInfo (
         $condition1 , [ 'contentid' , 'desc' ] , [ 'typeid' , 'content' ]
      );
      $infos[ 'wifi_notice' ] = '';
      if ( $getWifiContentsOneInfo && $getWifiContentsOneInfo->content )
      {
         $infos[ 'wifi_notice' ] = (String)$getWifiContentsOneInfo->content;
      }

      // 获取wifi时长
      $condition2[] = [ 'user_id' , '=' , $user->id ];
      $condition2[] = [ 'status' , '=' , 1 ];
      $getWifiUserTimesOneInfo = WifiUserTimesDao::getWifiUserTimesOneInfo (
         $condition2 , [ 'timesid' , 'desc' ] , [ 'user_wifi_time' ]
      );

      $infos[ 'user_wifi_time' ] = 0;
      if ( $getWifiUserTimesOneInfo && $getWifiUserTimesOneInfo->user_wifi_time )
      {
         $infos[ 'user_wifi_time' ] = strtotime ($getWifiUserTimesOneInfo->user_wifi_time);
      }

      return JsonBuilder::Success ( $infos ,'wifi无线产品列表');
   }

   /**
    * Func 充值记录
    * @param Request $request
    * @return Json
    */
   public function list_recharge_info(WifiPayRequest $request)
   {
      $user            = $request->user ();
      $param           = $request->only ( [ 'page' ] );
      $param[ 'page' ] = max ( 1 , intval ( $param[ 'page' ] ) );

      // 获取充值明细
      $condition[] = [ 'user_id' , '=' , $user->id ];
      // 状态(0:关闭,1:待支付,2:支付失败,3:支付成功-WIFI充值中,4:支付成功-WIFI充值成功,5:支付成功-充值失败,6:支付成功-退款成功)
      $condition[] = [ 'status' , '>' , 0 ];

      // 获取的字段
      $fieldArr = [
         'orderid' , 'wifi_name' , 'order_days' , 'order_number' , 'order_unitprice' ,
         'order_totalprice' , 'payment_name' , 'created_at' , 'pay_time' , 'succeed_time' ,
         'defeated_time' , 'refund_time' , 'cancle_time' , 'status' ,
      ];
      $infos    = WifiOrdersDao::getWifiOrdersListInfo (
         $condition , [ 'orderid' , 'desc' ] ,
         [ 'page' => $param['page'] , 'limit' => self::$api_wifi_page_limit ] ,
         $fieldArr
      )->toArray()['data'];

      return JsonBuilder::Success ( $infos,'wifi充值记录列表' );
   }

   /**
    * Func 检测是否在线
    * @param Request $request
    * @return Json
    */
   public function check_wifi_status_info(WifiPayRequest $request)
   {
      $user = $request->user ();

      // 获取wifi数据
      $condition[] = [ 'user_id' , '=' , $user->id ];
      $getWifiUserTimesOneInfo = WifiUserTimesDao::getWifiUserTimesOneInfo (
         $condition , [ 'timesid' , 'desc' ] , [ 'wif_psessionid' , 'user_wifi_time' ]
      );
      // wifi处于为连接状态
      $infos['status'] = 0;
      if($getWifiUserTimesOneInfo
         && $getWifiUserTimesOneInfo->wif_psessionid != ''
         && strtotime ($getWifiUserTimesOneInfo->user_wifi_time) > time())
      {
         $produce = Factory::produce(1);
         $checkAccountOnline = $produce->checkAccountOnline(
            ['psessionid'=>$getWifiUserTimesOneInfo->wif_psessionid]
         );
         $infos['status'] = $checkAccountOnline['status'] == true ? 1: 0;
      }
      // status 值为1 在线 0:不在线
      return JsonBuilder::Success ( $infos,'检测wifi状态');
   }


   /**
    * Func wifi上线
    * @param Request $request
    * @return Json
    */
   public function up_wifi_status_info(WifiPayRequest $request)
   {
      $user = $request->user ();

      // TODO.....验证参数
      $param = $request->only ( [ 'authType' , 'deviceMac', 'ssid', 'terminalIp', 'terminalMac' ] );

      // 获取wifi数据
      $condition[] = [ 'user_id' , '=' , $user->id ];
      $getWifiUserTimesOneInfo = WifiUserTimesDao::getWifiUserTimesOneInfo (
         $condition , [ 'timesid' , 'desc' ] , [ 'timesid','wif_psessionid','user_wifi_time' ]
      );
      // 未充值wifi,请先充值
      if($getWifiUserTimesOneInfo && strtotime ($getWifiUserTimesOneInfo->user_wifi_time) < time() )
      {
         return JsonBuilder::Error ( '请先购买wifi' );
      }
      // 华为上线
      $produce = Factory::produce(1);
      $param1['ssid']  =  $param['ssid'];
      $param1['authType']  =  $param['authType'];
      $param1['deviceMac']  =  $param['deviceMac'];
      $param1['terminalIp']  =  $param['terminalIp'];
      $param1['terminalMac']  =  $param['terminalMac'];
      $param1['userName']  =  $getWifiUserTimesOneInfo['user_wifi_name'];
      $param1['password']  =  $getWifiUserTimesOneInfo['user_wifi_password'];
      $AccountOnline = $produce->AccountOnline($param1);
      if($AccountOnline['status'] == true)
      {
         $saveData['wif_psessionid'] = (String)$AccountOnline['data']['psessionid'];
         WifiUserTimesDao::addOrUpdateWifiUserTimesInfo ($saveData,$getWifiUserTimesOneInfo['timesid']);
         return JsonBuilder::Success ( '上线成功' );
      } else {
         return JsonBuilder::Error ( '上线失败,请稍后重试' );
      }
   }

   /**
    * Func wifi下线
    * @param Request $request
    * @return Json
    */
   public function down_wifi_status_info(WifiPayRequest $request)
   {
      $user = $request->user ();

      // 获取wifi数据
      $condition[] = [ 'user_id' , '=' , $user->id ];
      $getWifiUserTimesOneInfo = WifiUserTimesDao::getWifiUserTimesOneInfo (
         $condition , [ 'timesid' , 'desc' ] , [ 'timesid','wif_psessionid' ]
      );
      // wifi处于为连接状态
      if($getWifiUserTimesOneInfo && $getWifiUserTimesOneInfo->wif_psessionid != '' )
      {
         $produce = Factory::produce(1);
         $checkAccountOnline = $produce->AccountOffline(
            ['psessionid'=>$getWifiUserTimesOneInfo->wif_psessionid]
         );
         if($checkAccountOnline['status'] == true)
         {
            $saveData['wif_psessionid'] = '';
            WifiUserTimesDao::addOrUpdateWifiUserTimesInfo ($saveData,$getWifiUserTimesOneInfo['timesid']);
            return JsonBuilder::Success ( '下线成功' );
         } else {
            return JsonBuilder::Error ( '下线失败,请稍后重试' );
         }
      } else {
         return JsonBuilder::Error ( '下线失败,请稍后重试' );
      }
   }



}
