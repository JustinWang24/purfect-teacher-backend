<?php
   namespace App\Http\Controllers\Operator;

   use App\Utils\FlashMessageBuilder;
   use App\Http\Controllers\Controller;
   use Illuminate\Support\Facades\Cache;

   use App\Http\Requests\Backstage\WifiOrderRequest;

   use App\Dao\Wifi\Backstage\UsersDao; // 用户信息
   use App\Dao\Wifi\Backstage\WifiOrdersDao; // 订单列表
   class WifiOrderController extends Controller
   {
      public function __construct()
      {
         $this->middleware('auth');
      }

      /**
       * Func wifi订单列表
       * @param WifiOrderRequest $request
       * @return view
       */
      public function list(WifiOrderRequest $request)
      {
         $param = $request->only ( [ 'school_id' , 'campus_id' , 'page','keywords' ] );
         $param[ 'page' ] = $request->input ( 'page' , 1 );

         // 查询条件
         $condition[] = [ 'orderid' , '>' , 0 ];
         // 状态
         if ( isset( $param[ 'status' ] ) && $param[ 'status' ] )
         {
            $condition[] = [ 'wifi_orders.school_id' , '=' , $param[ 'status' ] ];
         }
         // wifi 类型
         if ( isset( $param[ 'wifi_type' ] ) && $param[ 'wifi_type' ] )
         {
            $condition[] = [ 'wifi_orders.wifi_type' , '=' , $param[ 'wifi_type' ] ];
         }
         // 学校id
         if ( isset( $param[ 'school_id' ] ) && $param[ 'school_id' ] )
         {
            $condition[] = [ 'wifi_orders.school_id' , '=' , $param[ 'school_id' ] ];
         }
         // 校区id
         if ( isset( $param[ 'campus_id' ] )  && $param[ 'campus_id' ])
         {
            $condition[] = [ 'wifi_orders.campus_id' , '=' , $param[ 'campus_id' ] ];
         }
         // 支付方式
         if ( isset( $param[ 'paymentid' ] )  && $param[ 'paymentid' ])
         {
            $condition[] = [ 'wifi_orders.paymentid' , '=' , $param[ 'paymentid' ] ];
         }
         // 搜索关键词
         if ( isset( $param[ 'keywords' ] )  && $param[ 'keywords' ])
         {
            $condition[] = [ 'users.mobile' , 'like' , '%'.strip_tags($param[ 'keywords' ]).'%' ];
         }
		   
         // 获取字段
         $fieldArr = [ 'wifi_orders.*' , 'users.name' , 'users.mobile' , 'schools.name as school_name' ];

         // 获取用户信息
         $joinArr = [
            [ "users" , 'wifi_orders.user_id' , '=' , 'users.id' ] ,
            [ "schools" , 'wifi_orders.school_id' , '=' , 'schools.id' ] ,
         ];

         // 获取数据
         $dataList = WifiOrdersDao::getWifiOrdersListInfo (
            $condition , [ 'wifi_orders.orderid' , 'desc' ] ,
            [ 'page' => $param[ 'page' ] , 'limit' => self::$manger_wifi_page_limit ] ,
            $fieldArr , $joinArr
         );

         // 返回数据
         $this->dataForView[ 'dataList' ]            = $dataList;
         $this->dataForView[ 'paymentidArr' ]        = WifiOrdersDao::$paymentidArr;
         $this->dataForView[ 'manageWifiStatusArr' ] = WifiOrdersDao::$manageWifiStatusArr;

         return view ( 'manager_wifi.wifiOrder.list' , $this->dataForView );

      }


      /**
       * Func wifi订单详情
       * @param WifiOrderRequest $request
       * @return view
       */
      public function detail(WifiOrderRequest $request)
      {
         $param = $request->only ( [ 'trade_sn' ] );

         // 查询条件
         $condition[] = [ 'trade_sn' , '=' , $param['trade_sn'] ];

         // 获取字段
         $fieldArr = [ 'wifi_orders.*' , 'schools.name as school_name' , 'campuses.name as campuses_name' ];

         // 获取用户信息
         $joinArr = [
            [ "schools" , 'wifi_orders.school_id' , '=' , 'schools.id' ] ,
            [ "campuses" , 'wifi_orders.campus_id' , '=' , 'campuses.id' ] ,
         ];

         // 获取数据
         $dataOne['getWifiOrdersOneInfo'] = WifiOrdersDao::getWifiOrdersOneInfo (
            $condition , [ 'wifi_orders.orderid' , 'desc' ] ,
            $fieldArr , $joinArr
         );

         // 获取用户信息
         if ( ! empty( $dataOne['getWifiOrdersOneInfo'] ) && $dataOne['getWifiOrdersOneInfo']->user_id )
         {
            $dataOne['getUsersOneInfo'] = UsersDao::getUsersOneInfo (
               [ [ 'id' , '=' , $dataOne['getWifiOrdersOneInfo']->user_id ] ] , [ 'id' , 'desc' ] , [ '*' ]
            );
         }
         $dataOne['manageWifiStatusArr'] = WifiOrdersDao::$manageWifiStatusArr;

         // 返回数据
         $this->dataForView[ 'dataOne' ] = $dataOne;

         return view ( 'manager_wifi.wifiOrder.detail' , $this->dataForView );

      }
   }