<?php
   namespace App\Http\Controllers\Operator;

   use App\Utils\FlashMessageBuilder;
   use App\Http\Controllers\Controller;
   use Illuminate\Support\Facades\Cache;

   use App\Http\Requests\Backstage\WifiNoticeRequest;

   use App\Dao\Wifi\Backstage\WifiNoticesDao;
   class WifiNoticeController extends Controller
   {
      public function __construct()
      {
         $this->middleware('auth');
      }
      /**
       * Func wifi文档列表
       * @param WifiRequest $request
       * @return view
       */
      public function list(WifiNoticeRequest $request)
      {
         $param = $request->only ( [ 'page'] );

         // 查询条件
         $condition[] = [ 'noticeid' , '>' , 0 ];

         // 获取字段
         $fieldArr = [ '*' ];

         $joinArr = [];

         $dataList = WifiNoticesDao::getWifiNoticesListInfo (
            $condition , [ [ 'sort' , 'desc' ] ] , [ 'page' => $param['page'] , 'limit' => self::$manger_wifi_page_limit ] ,
            $fieldArr , $joinArr
         );

         // 返回数据
         $this->dataForView[ 'dataList' ] = $dataList;

         return view ( 'manager_wifi.wifiNotice.list' , $this->dataForView );

      }

      /**
       * Func wifi文档添加
       * @param WifiRequest $request
       * @return view
       */
      public function add(WifiNoticeRequest $request)
      {
         // 获取wifi产品
         $manageWifiArr = WifisDao::$manageWifiArr;

         // 提交数据保存
         if($request->isMethod('post'))
         {
            // 表单要插入的字段信息
            $param1 = self::getPostParamInfo ( $request->infos , [
                  'notice_title' , 'notice_content' ,
               ]
            );

            // 附加数据
            $param2 = [];

            // 验证数据是否重复提交
            $dataSign = sha1 ( $request->user()->id . 'add' );

            if ( Cache::has ( $dataSign ) )
            {
               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '您提交太快了，先歇息一下');
               return redirect()->route('manager_wifi.wifinotice.add');
            }

            if ( WifiNoticesDao::addOrUpdateWifiNoticesInfo ( array_merge ( $param1 , $param2 ) ) )
            {
               // 生成重复提交签名
               Cache::put ( $dataSign , $dataSign , 10 );

               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::SUCCESS , '数据添加成功');
               return redirect()->route('manager_wifi.wifinotice.list');
            } else {
               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '数据添加失败,请稍后重试' );
               return redirect()->route('manager_wifi.wifinotice.add');
            }
         }

         return view ( 'manager_wifi.wifinotice.add' );
      }


      /**
       * Func wifi文档修改
       * @param WifiRequest $request
       * @return view
       */
      public function edit(WifiNoticeRequest $request)
      {
         $param = $request->only ( [ 'noticeid', ] );

         if ( !intval ( $param[ 'noticeid' ] ) )
         {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '参数错误' );
            return redirect ()->route ( 'manager_wifi.wifinotice.list' );
         }

         // 获取数据信息
         $getWifiNoticesOneInfo = WifiNoticesDao::getWifiNoticesOneInfo (
            [ [ 'noticeid' , '=' , $param[ 'noticeid' ] ] ] , [ [ 'noticeid' , 'desc' ] ] ,[ '*' ]
         )->toArray();

         if ( empty( $getWifiNoticesOneInfo ) )
         {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '参数错误' );
            return redirect ()->route ( 'manager_wifi.wifinotice.list' );
         }

         // 提交数据保存
         if($request->isMethod('post'))
         {
            // 表单要插入的字段信息
            $param1 = self::getPostParamInfo ( $request->infos , [
                  'notice_title' , 'notice_content' ,
               ]
            );

            // 附加数据
            $param2 = [];

            // 验证数据是否重复提交
            $dataSign = sha1 ( $request->user()->id . 'edit' );

            if ( Cache::has ( $dataSign ) )
            {
               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '您提交太快了，先歇息一下' );
               return redirect ()->route ( 'manager_wifi.wifinotice.edit' , [ 'noticeid' => $getWifiNoticesOneInfo[ 'noticeid' ] ] );
            }

            if ( WifiNoticesDao::addOrUpdateWifiNoticesInfo ( array_merge ( $param1 , $param2 ) , $getWifiNoticesOneInfo[ 'noticeid' ] ) )
            {
               // 生成重复提交签名
               Cache::put ( $dataSign , $dataSign , 10 );

               FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'数据更新成功');

               return redirect()->route('manager_wifi.wifinotice.list');

            } else {
               FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'数据更新失败,请稍后重试');

               return redirect ()->route ( 'manager_wifi.wifinotice.edit' , [ 'noticeid' => $getWifiNoticesOneInfo[ 'noticeid' ] ] );
            }
         }

         $this->dataForView[ 'detailOne' ] = $getWifiNoticesOneInfo;
         // TODO.....
         return view ( 'manager_wifi.wifinotice.edit' , $this->dataForView );
      }

   }