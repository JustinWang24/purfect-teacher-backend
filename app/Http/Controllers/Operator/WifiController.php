<?php
   namespace App\Http\Controllers\Operator;

   use App\Utils\FlashMessageBuilder;
   use App\Http\Controllers\Controller;
   use Illuminate\Support\Facades\Cache;

   use App\Http\Requests\Backstage\WifiRequest;

   use App\Dao\Wifi\Backstage\WifisDao; // 产品
   class WifiController extends Controller
   {
      public function __construct()
      {
         $this->middleware('auth');
      }
      /**
       * Func wifi产品列表
       * @param WifiRequest $request
       * @return view
       */
      public function list(WifiRequest $request)
      {
         $param = $request->only ( [ 'school_id','campus_id','page' ] );
         $param[ 'page' ] = $request->input ( 'page' , 1 );

         // 查询条件
         $condition[] = [ 'wifiid' , '>' , 0 ];

         // 学校id
         if ( isset( $param[ 'school_id' ] )  && $param[ 'school_id' ])
         {
            $condition[] = [ 'school_id' , '=' , $param[ 'school_id' ] ];
         }
         // 校区id
         if ( isset( $param[ 'campus_id' ] )  && $param[ 'campus_id' ])
         {
            $condition[] = [ 'campus_id' , '=' , $param[ 'campus_id' ] ];
         }

         // 获取字段
         $fieldArr = [ 'wifis.*' , 'schools.name as schools_name' , 'campuses.name as campuses_name' ];

         // 获取学校和校区
         $joinArr = [
            [ "schools" , 'wifis.school_id' , '=' , 'schools.id' ] ,
            [ "campuses" , 'wifis.campus_id' , '=' , 'campuses.id' ] ,
         ];

         $wifiList = WifisDao::getWifisListInfo (
            $condition , [ 'wifis.wifi_sort' , 'desc' ] ,
            [ 'page' => $param[ 'page' ] , 'limit' => self::$manger_wifi_page_limit ] ,
            $fieldArr , $joinArr
         );

         // 返回数据
         $this->dataForView[ 'wifiList' ] = $wifiList;

         return view ( 'manager_wifi.wifi.list' , $this->dataForView );

      }

      /**
       * Func wifi产品修改
       * @param WifiRequest $request
       * @return view
       */
      public function add(WifiRequest $request)
      {
         // 获取wifi产品
         $manageWifiArr = WifisDao::$manageWifiArr;

         // 提交数据保存
         if ( $request->isMethod ( 'post' ) )
         {
            // 表单要插入的字段信息
            $param1 = self::getPostParamInfo ( $request->infos , [
                  'school_id' , 'campus_id' , 'mode' , 'wifi_days' , 'wifi_type' ,
                  'wifi_oprice' , 'wifi_price' ,'wifi_sort' ,
               ]
            );

            // 附加数据
            $manageWifiArr1        = array_column ( $manageWifiArr , 'name' , 'id' );
            $param2[ 'wifi_name' ] = (String)$manageWifiArr1[ $param1[ 'wifi_type' ] ]; // wifi类型名称
            if ( empty( $param2[ 'wifi_name' ] ) )
            {
               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '请选择WIFI种类');
               return redirect()->route('manager_wifi.wifi.add');
            }
            // 验证数据是否重复提交
            $dataSign = sha1 ( $request->user()->id . 'add' );

            if ( Cache::has ( $dataSign ) )
            {
               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '您提交太快了，先歇息一下');
               return redirect()->route('manager_wifi.wifi.add');
            }
            if ( WifisDao::addOrUpdateWifisInfo ( array_merge ( $param1 , $param2 ) ) )
            {
               // 生成重复提交签名
               Cache::put ( $dataSign , $dataSign , 10 );

               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::SUCCESS , '数据添加成功');
               return redirect()->route('manager_wifi.wifi.list');
            } else {
               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '数据添加失败,请稍后重试' );
               return redirect()->route('manager_wifi.wifi.add');
            }
         }

         $this->dataForView[ 'manageWifiArr' ] = $manageWifiArr;

         return view ( 'manager_wifi.wifi.add' , $this->dataForView );
      }


      /**
       * Func wifi产品修改
       * @param WifiRequest $request
       * @return view
       */
      public function edit(WifiRequest $request)
      {
         $param = $request->only ( [ 'wifiid', ] );

         if ( !intval ( $param[ 'wifiid' ] ) )
         {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '参数错误' );
            return redirect ()->route ( 'manager_wifi.wifi.list' );
         }

         // 获取数据信息
         $getWifisOneInfo = WifisDao::getWifisOneInfo (
            [ [ 'wifiid' , '=' , $param[ 'wifiid' ] ] ] , [ 'wifiid' , 'desc' ] ,[ '*' ]
         )->toArray();

         if ( empty( $getWifisOneInfo ) )
         {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '参数错误' );
            return redirect ()->route ( 'manager_wifi.wifi.list' );
         }

         // 获取wifi产品
         $manageWifiArr = WifisDao::$manageWifiArr;

         // 提交数据保存
         if($request->isMethod('post'))
         {
            // 表单要插入的字段信息
            $param1 = self::getPostParamInfo ( $request->infos , [
                  'school_id' , 'campus_id' , 'mode' , 'wifi_days' , 'wifi_type' ,
                  'wifi_oprice' , 'wifi_price' ,'wifi_sort' ,
               ]
            );

            // 附加数据
            $manageWifiArr1         = array_column ( $manageWifiArr , 'name' , 'id' );
            $param2[ 'wifi_name' ] = (String)$manageWifiArr1[ $param1[ 'wifi_type' ] ]; // wifi类型名称
            if ( empty( $param2[ 'wifi_name' ] ) )
            {
               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '请选择WIFI种类' );
               return redirect ()->route ( 'manager_wifi.wifi.edit' , [ 'wifid' => $getWifisOneInfo[ 'wifid' ] ] );
            }
            // 验证数据是否重复提交
            $dataSign = sha1 ( $request->user()->id . 'edit' );

            if ( Cache::has ( $dataSign ) )
            {
               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '您提交太快了，先歇息一下' );
               return redirect ()->route ( 'manager_wifi.wifi.edit' , [ 'wifid' => $getWifisOneInfo[ 'wifid' ] ] );
            }

            if ( WifisDao::addOrUpdateWifisInfo ( array_merge ( $param1 , $param2 ) , $getWifisOneInfo[ 'wifiid' ] ) )
            {
               // 生成重复提交签名
               Cache::put ( $dataSign , $dataSign , 10 );

               FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'数据更新成功');

               return redirect()->route('manager_wifi.wifi.list');

            } else {
               FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'数据更新失败,请稍后重试');

               return redirect ()->route ( 'manager_wifi.wifi.edit' , [ 'wifid' => $getWifisOneInfo[ 'wifid' ] ] );
            }
         }

         $this->dataForView[ 'infos' ] = $getWifisOneInfo; // 数据信息
         $this->dataForView[ 'manageWifiArr' ] = $manageWifiArr; // wifi 类型
         // TODO.....
         return view ( 'manager_wifi.wifi.edit' , $this->dataForView );
      }

   }