<?php
   namespace App\Http\Controllers\Operator;

   use App\Utils\FlashMessageBuilder;
   use App\Http\Controllers\Controller;
   use Illuminate\Support\Facades\Cache;

   use App\Http\Requests\Backstage\WifiContentRequest;

   use App\Dao\Wifi\Backstage\WifiContentsDao;
   class WifiContentController extends Controller
   {
      public function __construct()
      {
         $this->middleware('auth');
      }
      /**
       * Func wifi公告列表
       * @param WifiRequest $request
       * @return view
       */
      public function list(WifiContentRequest $request)
      {
         $param           = $request->only ( [ 'page' ,'school_id' ,'campus_id' ] );
         $param[ 'page' ] = $request->input ( 'page' , 1 );

         // 查询条件
         $condition[] = [ 'wifi_contents.contentid' , '>' , 0 ];

         // 学校
         if ( isset( $param[ 'school_id' ] ) && $param[ 'school_id' ] )
         {
            $condition[] = [ 'wifi_contents.school_id' , '=' , $param[ 'school_id' ] ];
         }

         // 校区
         if ( isset( $param[ 'campus_id' ] ) && $param[ 'campus_id' ] )
         {
            $condition[] = [ 'wifi_contents.campus_id' , '=' , $param[ 'campus_id' ] ];
         }

         // 获取字段
         $fieldArr = [ 'wifi_contents.*', 'schools.name as schools_name', 'campuses.name as campuses_name' ];

         $joinArr = [
            [ "schools" , 'wifi_contents.school_id' , '=' , 'schools.id' ] ,
            [ "campuses" , 'wifi_contents.campus_id' , '=' , 'campuses.id' ] ,
         ];

         $dataList = WifiContentsDao::getWifiContentsListInfo (
            $condition , [ 'contentid' , 'desc' ] ,
            [ 'page' => $param[ 'page' ] , 'limit' => self::$manger_wifi_page_limit ] ,
            $fieldArr , $joinArr
         );

         // 返回数据
         $this->dataForView[ 'dataList' ] = $dataList;
         $this->dataForView['wifiContentsTypeArr'] = WifiContentsDao::$wifiContentsTypeArr;

         return view ( 'manager_wifi.wifiContent.list' , $this->dataForView );
      }

      /**
       * Func wifi公告添加
       * @param WifiRequest $request
       * @return view
       */
      public function add(WifiContentRequest $request)
      {
         // 提交数据保存
         if($request->isMethod('post'))
         {
            // 表单要插入的字段信息
            $param1 = self::getPostParamInfo (
               $request->infos ,
               [ 'typeid' , 'school_id' , 'campus_id' , 'content' ]
            );

            // 附加数据
            $param2 = [];

            // 验证数据是否重复提交
            $dataSign = sha1 ( $request->user()->id . 'add' );

            if ( Cache::has ( $dataSign ) )
            {
               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '您提交太快了，先歇息一下');

               return redirect()->route('manager_wifi.wifiContent.add')->withInput();
            }

            // 检索是否存在
            $condition[] = [ 'campus_id' , '=' , $param1[ 'campus_id' ] ];
            $condition[] = [ 'typeid' , '=' , $param1[ 'typeid' ] ];
            if ( WifiContentsDao::getWifiContentsStatistics ( $condition , $mode = 'count' ) > 0 )
            {
               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '校区所对应的类型已存在' );
               return redirect ()->route ( 'manager_wifi.wifiContent.add' )->withInput();
            }

            if ( WifiContentsDao::addOrUpdateWifiContentsInfo ( array_merge ( $param1 , $param2 ) ) )
            {
               // 生成重复提交签名
               Cache::put ( $dataSign , $dataSign , 10 );

               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::SUCCESS , '数据添加成功');

               return redirect()->route('manager_wifi.wifiContent.list')->withInput();

            } else {

               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '数据添加失败,请稍后重试' );

               return redirect()->route('manager_wifi.wifiContent.add')->withInput();
            }
         }
         $this->dataForView['wifiContentsTypeArr'] = WifiContentsDao::$wifiContentsTypeArr;

         return view ( 'manager_wifi.wifiContent.add' , $this->dataForView );
      }


      /**
       * Func wifi公告修改
       * @param WifiRequest $request
       * @return view
       */
      public function edit(WifiContentRequest $request)
      {
         $param = $request->only ( [ 'contentid', ] );

         if ( !intval ( $param[ 'contentid' ] ) )
         {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '参数错误' );
            return redirect ()->route ( 'manager_wifi.wifiContent.list' )->withInput();
         }

         // 获取数据信息
         $getWifiContentsOneInfo = WifiContentsDao::getWifiContentsOneInfo (
            [ [ 'contentid' , '=' , $param[ 'contentid' ] ] ] , [ 'contentid' , 'desc' ] ,[ '*' ]
         )->toArray();

         if ( empty( $getWifiContentsOneInfo ) )
         {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '参数错误' );

            return redirect ()->route ( 'manager_wifi.wifiContent.list' )->withInput();
         }

         // 提交数据保存
         if($request->isMethod('post'))
         {
            // 表单要插入的字段信息
            $param1 = self::getPostParamInfo ( $request->infos , [ 'content'] );

            // 附加数据
            $param2 = [];

            // 验证数据是否重复提交
            $dataSign = sha1 ( $request->user()->id . 'edit' );

            if ( Cache::has ( $dataSign ) )
            {
               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '您提交太快了，先歇息一下' );

               return redirect ()->route ( 'manager_wifi.wifiContent.edit' , [ 'contentid' => $getWifiContentsOneInfo[ 'contentid' ] ] )->withInput();

            }

            if ( WifiContentsDao::addOrUpdateWifiContentsInfo ( array_merge ( $param1 , $param2 ) , $getWifiContentsOneInfo[ 'contentid' ] ) )
            {
               // 生成重复提交签名
               Cache::put ( $dataSign , $dataSign , 10 );

               FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'数据更新成功');

               return redirect()->route('manager_wifi.wifiContent.list')->withInput();

            } else {
               FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'数据更新失败,请稍后重试');

               return redirect ()->route ( 'manager_wifi.wifiContent.edit' , [ 'contentid' => $getWifiContentsOneInfo[ 'contentid' ] ] )->withInput();
            }
         }

         $this->dataForView[ 'dataOne' ] = $getWifiContentsOneInfo;

         return view ( 'manager_wifi.wifiContent.edit' , $this->dataForView );
      }

      /**
       * Func wifi公告删除
       * @param WifiRequest $request
       * @return view
       */
      public function delete(WifiContentRequest $request)
      {
         $param = $request->only ( [ 'contentid', ] );

         if ( !intval ( $param[ 'contentid' ] ) )
         {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '参数错误' );
            return redirect ()->route ( 'manager_wifi.wifiContent.list' )->withInput();
         }

         // 获取数据信息
         $getWifiContentsOneInfo = WifiContentsDao::getWifiContentsOneInfo (
            [ [ 'contentid' , '=' , $param[ 'contentid' ] ] ] , [ 'contentid' , 'desc' ] ,[ '*' ]
         )->toArray();

         if ( empty( $getWifiContentsOneInfo ) )
         {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '参数错误' );
            return redirect ()->route ( 'manager_wifi.wifiContent.list' )->withInput();
         }

         // 删除条件
         $condition[] = [ 'contentid' , '=' , $param['contentid'] ];

         if ( WifiContentsDao::delWifiContentsInfo ( $condition , [] , true ) )
         {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::SUCCESS , '数据删除成功' );
         } else {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '数据删除失败,请稍后重试' );
         }
         return redirect ()->route ( 'manager_wifi.wifiContent.list' )->withInput();
      }
   }