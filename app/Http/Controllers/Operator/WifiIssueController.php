<?php
   namespace App\Http\Controllers\Operator;

   use App\Events\SystemNotification\WifiIssueEvent;
   use App\Utils\FlashMessageBuilder;
   use App\Http\Controllers\Controller;
   use Illuminate\Support\Facades\Cache;

   use App\Http\Requests\Backstage\WifiIssueRequest;

   use App\Dao\Wifi\Backstage\UsersDao; // 用户
   use App\Dao\Wifi\Backstage\WifiIssuesDao; // 报修
   use App\Dao\Wifi\Backstage\WifiIssueTypesDao; // 报修类型
   use App\Dao\Wifi\Backstage\WifiIssueDisposesDao; // 报修日志
   class WifiIssueController extends Controller
   {
      public function __construct()
      {
         $this->middleware('auth');
      }
      /**
       * Func 报修列表
       * @param WifiOrderRequest $request
       * @return view
       */
      public function list(WifiIssueRequest $request)
      {
         $param = $request->only ( [ 'school_id' , 'campus_id','keywords' ] );
         $param[ 'page' ] = $request->input ( 'page' , 1 );

         // 查询条件
         $condition[] = [ 'issueid' , '>' , 0 ];

         // 学校
         if ( isset( $param[ 'school_id' ] ) && $param[ 'school_id' ] )
         {
            $condition[] = [ 'wifi_issues.school_id' , '=' , $param[ 'school_id' ] ];
         }

         // 校区
         if ( isset( $param[ 'campus_id' ] ) && $param[ 'campus_id' ] )
         {
            $condition[] = [ 'wifi_issues.campus_id' , '=' , $param[ 'campus_id' ] ];
         }

         // 搜索关键词
         if ( isset( $param[ 'keywords' ] )  && $param[ 'keywords' ])
         {
            $condition[] = [ 'wifi_issues.issue_mobile' , 'like' , '%'.strip_tags($param[ 'keywords' ]).'%' ];
         }
         // 状态
         if ( isset( $param[ 'status' ] ) && $param[ 'status' ] )
         {
            $condition[] = [ 'wifi_issue.status' , '=' , $param[ 'status' ] ];
         }
         // 获取字段
         $fieldArr = [
            'wifi_issues.*' , 'users.name' , 'users.mobile','schools.name as schools_name'
         ];

         // 关联表信息
         $joinArr = [
            [ "users" , 'wifi_issues.user_id' , '=' , 'users.id' ] ,
            [ "schools" , 'wifi_issues.school_id' , '=' , 'schools.id' ] ,
         ];

         // 获取数据
         $dataList = WifiIssuesDao::getWifiIssuesListInfo (
            $condition , [ 'wifi_issues.issueid' , 'desc' ] ,
            [ 'page' => $param[ 'page' ] , 'limit' => self::$manger_wifi_page_limit ] ,
            $fieldArr , $joinArr
         );

         // 返回数据
         $this->dataForView[ 'dataList' ]         = $dataList;
         $this->dataForView[ 'manageCommentArr' ] = WifiIssuesDao::$manageCommentArr;
         $this->dataForView[ 'manageStatusArr' ]  = WifiIssuesDao::$manageStatusArr;

         return view ( 'manager_wifi.wifiIssue.list' , $this->dataForView );

      }
      /**
       * Func 接单
       * @param WifiOrderRequest $request
       * @return view
       */
      public function edit(WifiIssueRequest $request)
      {
         $param = $request->only ( [ 'issueid' ] );

         // 查询条件
         $condition[] = [ 'issueid' , '=' , $param['issueid'] ];

         // 获取字段
         $fieldArr = [ 'wifi_issues.*' ];

         // 关联表信息
         $joinArr = [];

         // 获取数据
         $getWifiIssuesOneInfo = WifiIssuesDao::getWifiIssuesOneInfo (
            $condition , [ 'issueid' , 'desc' ] , $fieldArr , $joinArr
         );

         // 判断数据
         if ( !empty( $getWifiIssuesOneInfo ) )
         {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '参数错误');
         }

         // 处理数据
         $saveData['status'] = 2;
         $saveData['adminid'] = 0; // TODO...
         $saveData['admin_name'] = ''; // TODO.....
         $saveData['jiedan_time'] = date('Y-m-d H:i:s');
         if (WifiIssuesDao::addOrUpdateWifiIssuesInfo ( $saveData , $getWifiIssuesOneInfo->issueid ) )
         {
             //发送系统消息
             event(new WifiIssueEvent(WifiIssuesDao::getWifiIssuesOneInfo (
                 $condition , [ 'issueid' , 'desc' ] , $fieldArr , $joinArr
             )));

            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::SUCCESS , '操作成功');
            return redirect()->route('manager_wifi.wifiIssue.list');
         } else {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '操作失败,请稍后重试');
            return redirect()->route('manager_wifi.wifiIssue.list');
         }
      }


      /**
       * Func 报修详情
       * @param WifiOrderRequest $request
       * @return view
       */
      public function detail(WifiIssueRequest $request)
      {
         $param = $request->only ( [ 'issueid' ] );

         // 查询条件
         $condition[] = [ 'issueid' , '=' , $param['issueid'] ];

         // 获取字段
         $fieldArr = [
            'wifi_issues.*' , 'schools.name as schools_name' , 'campuses.name as campuses_name',
         ];

         // 关联表信息
         $joinArr = [
            [ "schools" , 'wifi_issues.school_id' , '=' , 'schools.id' ] ,
            [ "campuses" , 'wifi_issues.campus_id' , '=' , 'campuses.id' ] ,
         ];

         // 获取数据
         $dataOne['getWifiIssuesOneInfo'] = WifiIssuesDao::getWifiIssuesOneInfo (
            $condition , [ 'issueid' , 'desc' ] , $fieldArr , $joinArr
         );

         // 获取其他信息
         if ( ! empty( $dataOne['getWifiIssuesOneInfo'] ) && $dataOne['getWifiIssuesOneInfo']->user_id )
         {
            // 获取用户信息
            $dataOne['getUsersOneInfo'] = UsersDao::getUsersOneInfo (
               [ [ 'id' , '=' , $dataOne['getWifiIssuesOneInfo']->user_id ] ] , [ 'id' , 'desc' ] , [ '*' ]
            );
            // 获取处理信息
            $dataOne['getWifiIssueDisposesOneInfo'] = WifiIssueDisposesDao::getWifiIssueDisposesOneInfo (
               [ [ 'issueid' , '=' , $dataOne['getWifiIssuesOneInfo']->issueid ] ] , [ 'disposeid' , 'desc' ] , [ '*' ]
            );
         }

         // 返回数据
         $this->dataForView[ 'dataOne' ] = $dataOne;

         return view ( 'manager_wifi.wifiIssue.detail' , $this->dataForView );
      }

      /**
       * Func 报修提交
       * @param WifiOrderRequest $request
       * @return view
       */
      public function update(WifiIssueRequest $request)
      {
         $param = $request->only ( [ 'issueid' , 'typeone_id' , 'typetwo_id' , 'admin_desc' ] );

         // 查询条件
         $condition[] = [ 'issueid' , '=' , $param['issueid'] ];

         // 获取字段
         $fieldArr = [ 'wifi_issues.*' ];

         // 关联表信息
         $joinArr = [];

         // 获取数据
         $getWifiIssuesOneInfo = WifiIssuesDao::getWifiIssuesOneInfo (
            $condition , [ 'issueid' , 'desc' ] , $fieldArr , $joinArr
         );

         // 判断数据
         if ( empty( $getWifiIssuesOneInfo ) )
         {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '参数错误');
            return redirect()->route('manager_wifi.wifiIssue.detail',[ 'issueid' =>$getWifiIssuesOneInfo->issueid  ] )->withInput();
         }

         // 获取一级分类
         $getWifiIssueTypesOneInfo1 = WifiIssueTypesDao::getWifiIssueTypesOneInfo (
            [ [ 'typeid' , '=' , $param[ 'typeone_id' ] ] ] , [ 'typeid' , 'desc' ] ,
            [ 'typeid' , 'type_name' ]
         );
         if ( empty( $getWifiIssueTypesOneInfo1 ) || !$getWifiIssueTypesOneInfo1->typeid )
         {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '故障分类值错误');
            return redirect()->route('manager_wifi.wifiIssue.detail',[ 'issueid' =>$getWifiIssuesOneInfo->issueid  ] )->withInput();
         }

         // 获取二级分类
         $getWifiIssueTypesOneInfo2 = WifiIssueTypesDao::getWifiIssueTypesOneInfo (
            [ [ 'typeid' , '=' , $param[ 'typetwo_id' ] ] ] , [ 'typeid' , 'desc' ] ,
            [ 'typeid' , 'type_name' ]
         );
         if ( empty( $getWifiIssueTypesOneInfo2 ) || !$getWifiIssueTypesOneInfo2->typeid )
         {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '故障子类值错误');
            return redirect()->route('manager_wifi.wifiIssue.detail',[ 'issueid' =>$getWifiIssuesOneInfo->issueid  ] )->withInput();
         }

         // 处理数据
         $saveData[ 'status' ]      = 3;
         $saveData[ 'is_comment' ]  = 1;
         $saveData[ 'admin_name' ]  = '4007792525';
         $saveData[ 'admin_mobile' ]  = '4007792525';
         $saveData[ 'admin_desc' ]  = trim ( $param[ 'admin_desc' ] );
         $saveData[ 'chulis_time' ] = date ( 'Y-m-d H:i:s' );
         if ( WifiIssuesDao::addOrUpdateWifiIssuesInfo ( $saveData , $getWifiIssuesOneInfo->issueid ) )
         {
            // 记录日志状态
            $addData1[ 'status' ]    = $saveData[ 'status' ];
            $addData1[ 'issueid' ]   = (Int)$getWifiIssuesOneInfo->issueid;
            $addData1[ 'school_id' ] = (Int)$getWifiIssuesOneInfo->school_id;
            $addData1[ 'campus_id' ] = (Int)$getWifiIssuesOneInfo->campus_id;

            $addData1[ 'typeone_id' ]   = (Int)$getWifiIssueTypesOneInfo1->typeid;
            $addData1[ 'typeone_name' ] = (String)$getWifiIssueTypesOneInfo1->type_name;
            $addData1[ 'typetwo_id' ]   = (Int)$getWifiIssueTypesOneInfo2->typeid;
            $addData1[ 'typetwo_name' ] = (String)$getWifiIssueTypesOneInfo2->type_name;
            $addData1[ 'admin_desc' ] = (String)$saveData[ 'admin_desc' ];

            WifiIssueDisposesDao::addOrUpdateWifiIssueDisposesInfo ($addData1);

             //发送系统消息
             event(new WifiIssueEvent(WifiIssuesDao::getWifiIssuesOneInfo (
                 $condition , [ 'issueid' , 'desc' ] , $fieldArr , $joinArr
             )));

            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::SUCCESS , '操作成功');

            return redirect()->route('manager_wifi.wifiIssue.detail',[ 'issueid' =>$getWifiIssuesOneInfo->issueid  ] );

         } else {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '操作失败,请稍后重试');

            return redirect()->route('manager_wifi.wifiIssue.detail',[ 'issueid' =>$getWifiIssuesOneInfo->issueid  ] )->withInput();
         }
      }

   }
