<?php
   namespace App\Http\Controllers\Operator;

   use App\Utils\FlashMessageBuilder;
   use App\Http\Controllers\Controller;
   use Illuminate\Support\Facades\Cache;

   use App\Http\Requests\Backstage\WifiIssueRequest;

   use App\Dao\Wifi\Backstage\WifiIssuesDao; // 报修
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
         $param = $request->only ( [ 'school_id' , 'campus_id' ] );
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
            'wifi_issues.*' , 'users.name' , 'users.mobile',
         ];

         // 关联表信息
         $joinArr = [
            [ "users" , 'wifi_issues.user_id' , '=' , 'users.id' ] ,
         ];

         // 获取数据
         $dataOne = WifiIssuesDao::getWifiIssuesOneInfo (
            $condition , [ 'issueid' , 'desc' ] , $fieldArr , $joinArr
         );

         // 返回数据
         $this->dataForView[ 'dataOne' ]         = $dataOne;
         $this->dataForView[ 'manageCommentArr' ] = WifiIssuesDao::$manageCommentArr;
         $this->dataForView[ 'manageStatusArr' ]  = WifiIssuesDao::$manageStatusArr;

         return view ( 'manager_wifi.wifiIssue.detail' , $this->dataForView );
      }
   }