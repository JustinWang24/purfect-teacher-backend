<?php
   namespace App\Http\Controllers\Operator;

   use App\Utils\FlashMessageBuilder;
   use App\Http\Controllers\Controller;
   use Illuminate\Support\Facades\Cache;

   use App\Http\Requests\Backstage\WifiIssueTypeRequest;

   use App\Dao\Wifi\Backstage\WifiIssueTypesDao; // 报修分类
   class WifiIssueTypeController extends Controller
   {
      public function __construct()
      {
         $this->middleware('auth');
      }
      /**
       * Func wifi报修分类列表
       * @param WifiRequest $request
       * @return view
       */
      public function list(WifiIssueTypeRequest $request)
      {
         $param = $request->only ( [ 'keywords' , 'page' ] );
         $param[ 'page' ]     = $request->input ( 'page' , 1 );
         $param[ 'type_pid' ] = $request->input ( 'type_pid' , 0 );

         // 查询条件
         $condition[] = [ 'typeid' , '>' , 0 ];
         $condition[] = [ 'type_pid' , '=' , intval( $param[ 'type_pid' ] ) ];

         // 获取字段
         $fieldArr = [ '*'];

         // 连接表
         $joinArr = [];

         $dataList = WifiIssueTypesDao::getWifiIssueTypesListInfo (
            $condition , [ 'typeid' , 'asc' ] ,
            [ 'page' => $param[ 'page' ] , 'limit' => self::$manger_wifi_page_limit ] ,
            $fieldArr , $joinArr
         );

         // 返回数据
         $this->dataForView[ 'param' ] = $param;
         $this->dataForView[ 'dataList' ] = $dataList;

         return view ( 'manager_wifi.wifiIssueType.list' , $this->dataForView );

      }

      /**
       * Func 报修分类添加
       * @param WifiRequest $request
       * @return view
       */
      public function add(WifiIssueTypeRequest $request)
      {
         $param[ 'typeid' ]   = $request->input ( 'typeid' , 0 );
         $param[ 'type_pid' ] = $request->input ( 'type_pid' , 0 );

         // 提交数据保存
         if ( $request->isMethod ( 'post' ) )
         {
            // 表单要插入的字段信息
            $param1 = self::getPostParamInfo ( $request->infos ,
               [
                  'type_name'
               ]
            );
            $param2[ 'type_pid' ] = $param[ 'type_pid' ]; // 分类

            // 验证数据是否重复提交
            $dataSign = sha1 ( $request->user()->id . 'add' );

            if ( Cache::has ( $dataSign ) )
            {
               // FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '您提交太快了，先歇息一下' );
               // return redirect ()->route ( 'manager_wifi.wifiIssueType.add' , [ 'typeid' => $param[ 'typeid' ],'type_pid'=>$param[ 'type_pid' ] ] );
            }

            if ( WifiIssueTypesDao::addOrUpdateWifiIssueTypesInfo (array_merge ( $param1 , $param2 ) ) )
            {
               // 生成重复提交签名
               Cache::put ( $dataSign , $dataSign , 10 );
               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::SUCCESS , '数据添加成功');

               return redirect ()->route ( 'manager_wifi.wifiIssueType.list' , [ 'typeid' => $param[ 'typeid' ],'type_pid'=>$param[ 'type_pid' ] ] );

            }else {
               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '数据添加失败,请稍后重试' );

               return redirect()->route('manager_wifi.wifiIssueType.add', [ 'typeid' => $param[ 'typeid' ],'type_pid'=>$param[ 'type_pid' ] ]);
            }
         }

         return view ( 'manager_wifi.wifiIssueType.add' , $this->dataForView );
      }

      /**
       * Func 报修分类修改
       * @param WifiRequest $request
       * @return view
       */
      public function edit(WifiIssueTypeRequest $request)
      {
         $param[ 'typeid' ]   = $request->input ( 'typeid' , 0 );
         $param[ 'type_pid' ] = $request->input ( 'typeid' , 0 );

         if ( !intval ( $param[ 'typeid' ] ) )
         {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '参数错误' );

            return redirect ()->route ( 'manager_wifi.wifiIssueType.list', [ 'typeid' => $param[ 'typeid' ],'type_pid'=>$param[ 'type_pid' ] ] );
         }

         // 获取数据信息
         $getWifiIssueTypesOneInfo = WifiIssueTypesDao::getWifiIssueTypesOneInfo (
            [ [ 'typeid' , '=' , $param[ 'typeid' ] ] ] , [ 'typeid' , 'desc' ] ,[ '*' ]
         )->toArray();

         if ( empty( $getWifiIssueTypesOneInfo ) )
         {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '参数错误' );

            return redirect ()->route ( 'manager_wifi.wifiIssueType.list', [ 'typeid' => $param[ 'typeid' ],'type_pid'=>$getWifiIssueTypesOneInfo[ 'type_pid' ] ] );
         }

         // 提交数据保存
         if($request->isMethod('post'))
         {
            // 表单要插入的字段信息
            $param1 = self::getPostParamInfo ( $request->infos , [
                  'type_name' ,
               ]
            );

            // 附加数据
            $param2 = [];
            // 验证数据是否重复提交
            $dataSign = sha1 ( $request->user()->id . 'edit' );

            if ( Cache::has ( $dataSign ) )
            {
               // FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '您提交太快了，先歇息一下' );
               // return redirect ()->route ( 'manager_wifi.wifiIssueType.edit', [ 'typeid' => $param[ 'typeid' ],'type_pid'=>$getWifiIssueTypesOneInfo[ 'type_pid' ] ] );
            }

            if ( WifiIssueTypesDao::addOrUpdateWifiIssueTypesInfo ( array_merge ( $param1 , $param2 ) , $getWifiIssueTypesOneInfo[ 'typeid' ] ) )
            {
               // 生成重复提交签名
               Cache::put ( $dataSign , $dataSign , 10 );

               FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'数据更新成功');

               return redirect()->route('manager_wifi.wifiIssueType.list', [ 'typeid' => $param[ 'typeid' ],'type_pid'=>$getWifiIssueTypesOneInfo[ 'type_pid' ] ]);

            } else {
               FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'数据更新失败,请稍后重试');

               return redirect ()->route ( 'manager_wifi.wifiIssueType.edit', [ 'typeid' => $param[ 'typeid' ],'type_pid'=>$getWifiIssueTypesOneInfo[ 'type_pid' ] ] );
            }
         }

         $this->dataForView[ 'dataOne' ] = $getWifiIssueTypesOneInfo; // 数据信息

         return view ( 'manager_wifi.wifiIssueType.edit' , $this->dataForView );
      }

      /**
       * Func 报修分类删除
       * @param WifiRequest $request
       * @return view
       */
      public function delete(WifiIssueTypeRequest $request )
      {
         $param = $request->only ( [ 'typeid' , 'type_pid' ] );

         if ( !intval ( $param[ 'typeid' ] ) )
         {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '参数错误' );
            return redirect ()->route ( 'manager_wifi.wifiIssueType.list'  , [ 'type_pid' => $param[ 'type_pid' ] ]);
         }

         // TOOD....删除前判断是否有值。
         $condition[] = [ 'type_pid' , '=' , $param[ 'typeid' ] ];
         if ( WifiIssueTypesDao::getWifiIssueTypesStatistics ( $condition , $mode = 'count' ) > 0)
         {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'该分类下面有子类数据不能删除');
            return redirect ()->route ( 'manager_wifi.wifiIssueType.list'  , [ 'type_pid' => $param[ 'type_pid' ] ]);
         }

         // 查询条件
         $condition1[] = [ 'typeid' , '=' , $param['typeid'] ];

         WifiIssueTypesDao::delWifiIssueTypesInfo ( $condition1 , [] , true );

         FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'删除成功');

         return redirect ()->route ( 'manager_wifi.wifiIssueType.list'  , [ 'type_pid' => $param[ 'type_pid' ] ]);
      }
   }