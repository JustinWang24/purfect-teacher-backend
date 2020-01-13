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
use App\Http\Requests\Api\Wifi\WifiRequest;
use App\Http\Requests\Api\Wifi\WifiUserAgreementsRequest; // wifi协议
use App\Http\Controllers\Controller;

use App\Dao\Users\UserDao; // 用户
use App\Dao\Wifi\Api\WifiContentsDao; // 常用须知
use App\Dao\Wifi\Api\WifiConfigsDao; // wifi 配置
use App\Dao\Wifi\Api\WifiIssuesDao; // 报修单
use App\Dao\Wifi\Api\WifiUserAgreementsDao; // wifi协议
use App\Dao\Wifi\Api\WifiUserTimesDao; // 用户wifi时长和电话套餐
use Illuminate\Support\Facades\DB;
use App\BusinessLogic\WifiInterface\Factory;

class WifiController extends Controller
{

   /**
    * Func 校园网首页
    * @param Request $request
    * @return Json
    */
   public function index_wifi(WifiRequest $request)
   {
      $user = $request->user ();

      $user_id   = $user->id;
      $campus_id = $user->gradeUser->campus_id;

      if ( ! intval ( $campus_id ) || ! intval ( $user_id ) )
      {
         return JsonBuilder::Error ( '参数错误' );
      }
      // 获取通知须知+用网须知
      $condition[] = [ 'campus_id' , '=' , $campus_id ];
      $condition[] = [ 'status' , '=' , 1 ];
      // 获取学校和校区
      $joinArr = [];
      $contentsList = WifiContentsDao::getWifiContentsListInfo (
         $condition , [ 'contentid' , 'desc' ] ,
         [ 'page' => 1 , 'limit' => 10 ] ,
         [ 'typeid' , 'content' ],
         $joinArr
      )->toArray ()[ 'data' ];

      $contentsListArr = array_column($contentsList,'content','typeid');
	  // 类型(1:校园网通知,2:用网须知,3:充值通知,4:套餐说明,5:网络协议)
	  $infos['contentsList']['contents_1'] = (String)$contentsListArr[1];
	  $infos['contentsList']['contents_2'] = (String)$contentsListArr[2];
	  $infos['contentsList']['contents_3'] = (String)$contentsListArr[3];
	  $infos['contentsList']['contents_4'] = (String)$contentsListArr[4];
	  $infos['contentsList']['contents_5'] = (String)$contentsListArr[5];

      // 获取配置信息
      $condition1[] = [ 'campus_id' , '=' , $campus_id ];
      $condition1[] = [ 'status' , '=' , 1 ];

      // 获取的字段信息
      $fieldArr1    = [
         'config_imgurl1','config_imgurl2','config_imgurl3',
         'config_imgurl1_status','config_imgurl2_status','config_imgurl3_status'
      ];
      
	  $infos['wifiConfig'] = WifiConfigsDao::getWifiConfigsOneInfo(
         $condition1,['configid','desc'],$fieldArr1
      );

      // 获取是否有待处理的工单
      $condition2[]           = [ 'user_id' , '=' , $user_id ];
      $condition2[]           = [ 'status' , '<=' , 2 ]; // 状态(1:待处理，2:已接单，3:已完成,，4:已取消，5:已关闭)
      $infos[ 'issue_count' ] = (Int)WifiIssuesDao::getWifiIssuesStatistics ( $condition2 , 'count' );

      // 获取是否同意协议
      $condition3[] = [ 'user_id' , '=' , $user_id ];
      $infos[ 'wifi_is_agree' ] = (Int)WifiUserAgreementsDao::getWifiUserAgreementsStatistics ( $condition3 ,'count');
 
      return JsonBuilder::Success ( $infos , '校园网首页' );
   }

   /**
    * Func 用户同意wifi协议
    * @param Request $request
    * @return Json
    */
   public function edit_agreement_info(WifiRequest $request)
   {
      $user = $request->user ();
      if ( ! intval ( $user->id ) )
      {
         return JsonBuilder::Error ( '参数错误' );
      }
      // 获取是否同意协议
      $condition[] = [ 'user_id' , '=' , $user->id ];
      if(WifiUserAgreementsDao::getWifiUserAgreementsStatistics ( $condition , 'count' ) )
      {
         return JsonBuilder::Error ( '您已同意协议' );
      }

      // 添加数据
      $param1[ 'user_id' ] = $user->id;
      if ( WifiUserAgreementsDao::addOrUpdateWifiUserAgreementsInfo ( $param1 ) )
      {
         return JsonBuilder::Success ( '操作成功' );
      } else {
         return JsonBuilder::Error ( '操作失败,请稍后重试' );
      }
   }

   /**
    * Func 修改运营商信息
    * @param Request $request
    * @return Json
    */
   public function edit_operator_info(WifiRequest $request)
   {
      $user = $request->user ();

      $param = $request->only ( [ 'user_mobile_source' , 'user_mobile_password' ] );

      // 获取已存在的用户数据
      $condition[] = [ 'user_id' , '=' , $user->id ];
      $getWifiUserTimesOneInfo = WifiUserTimesDao::getWifiUserTimesOneInfo (
         $condition , [ 'timesid' , 'desc' ] , [ 'timesid' ]
      );
      $timesid = $getWifiUserTimesOneInfo ? $getWifiUserTimesOneInfo->timesid : 0;

      // 表单要插入的字段信息
      $param1 = self::getPostParamInfo ( $param , [ 'user_mobile_source' , 'user_mobile_password' ] );
      $param2[ 'user_id' ] = $user->id;
      $param2[ 'user_mobile_phone' ] = $user->mobile;
      $param2[ 'school_id' ] = $user->gradeUser->school_id;
      $param2[ 'campus_id' ] = $user->gradeUser->campus_id;

      if ( WifiUserTimesDao::addOrUpdateWifiUserTimesInfo ( array_merge ( $param1 , $param2 ) , $timesid ) )
      {
         return JsonBuilder::Success ( '操作成功' );
      } else {
         return JsonBuilder::Error ( '操作失败,请稍后重试' );
      }
   }

    /**
     * Func 帮助指南列表
     * @param Request $request
     * @return page
     */
    public function page_info(\Illuminate\Http\Request $request)
    {
        $dataList = DB::table('wifi_notices')->orderBy('sort')->get();

        $this->dataForView['dataList'] = $dataList;

        return view('wifi.page_list', $this->dataForView);
    }

    /**
     * Func 帮助指南详情
     * @param Request $request
     * @return page
     */
    public function page_view(\Illuminate\Http\Request $request)
    {
        $noticeid = $request->input('noticeid', 0);

        $dataOne = (object)null;
        $dataOne->notice_title = '';
        $dataOne->notice_content = '';

        if($noticeid) $dataOne = DB::table('wifi_notices')->where('noticeid',$noticeid)->first();

        $this->dataForView['dataOne'] = $dataOne;

        return view('wifi.page_view', $this->dataForView);
    }

}
