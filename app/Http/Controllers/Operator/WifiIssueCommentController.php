<?php
   namespace App\Http\Controllers\Operator;

   use App\Models\Wifi\Backstage\WifiIssues;
   use App\Utils\FlashMessageBuilder;
   use App\Http\Controllers\Controller;
   use Illuminate\Support\Facades\Cache;

   use App\Http\Requests\Backstage\WifiIssueCommentRequest;

   use App\Dao\Wifi\Backstage\UsersDao; // 用户
   use App\Dao\Wifi\Backstage\WifiIssueDao; // 报修信息
   use App\Dao\Wifi\Backstage\WifiIssueDisposesDao; // 报修处理
   use App\Dao\Wifi\Backstage\WifiIssueCommentsDao; // 报修评论
   class WifiIssueCommentController extends Controller
   {
      public function __construct()
      {
         $this->middleware('auth');
      }
      /**
       * Func 报修评论列表
       * @param WifiOrderRequest $request
       * @return view
       */
      public function list(WifiIssueCommentRequest $request)
      {
         $param = $request->only ( [ 'school_id' , 'campus_id','keywords' ] );
         $param[ 'page' ] = $request->input ( 'page' , 1 );
		
         // 查询条件
         $condition[] = [ 'commentid' , '>' , 0 ];
		 
		  // 学校
         if ( isset( $param[ 'school_id' ] ) && $param[ 'school_id' ] )
         {
            $condition[] = [ 'wifi_issue_comments.school_id' , '=' , $param[ 'school_id' ] ];
         }
		 
		  // 校区
         if ( isset( $param[ 'campus_id' ] ) && $param[ 'campus_id' ] )
         {
            $condition[] = [ 'wifi_issue_comments.campus_id' , '=' , $param[ 'campus_id' ] ];
         }
	
		  // 关键词搜搜
         if ( isset( $param[ 'keywords' ] ) && $param[ 'keywords' ] )
         {
		    // TODO...
           // $condition[] = [ 'wifi_issues.issue_name|wifi_issues.issue_mobile' , 'like' , '%'.strip_tags($param[ 'keywords' ]).'%' ];
         } 
         
		 // 状态
         if ( isset( $param[ 'status' ] ) && $param[ 'status' ] )
         {
            $condition[] = [ 'wifi_issue_comments.status' , '=' , $param[ 'status' ] ];
         }

         // 获取字段
         $fieldArr = [
            'wifi_issue_comments.*' , 'wifi_issues.issue_name' , 'wifi_issues.issue_mobile' ,
            'wifi_issues.issue_desc' , 'wifi_issues.addr_detail' , 'wifi_issues.admin_name' ,
            'wifi_issues.admin_mobile' , 'wifi_issues.admin_desc' , 'wifi_issues.created_at' ,
            'wifi_issues.typeone_name' , 'wifi_issues.typetwo_name' , 'wifi_issues.jiedan_time' ,
            'wifi_issues.chulis_time' , 'wifi_issues.typeone_name' , 'wifi_issues.typetwo_name' ,
            'wifi_issues.addr_detail' , 'wifi_issues.issue_desc' , 'wifi_issues.trade_sn',
            'schools.name as schools_name'
         ];

         // 关联表信息
         $joinArr = [
            [ "wifi_issues" , 'wifi_issue_comments.issueid' , '=' , 'wifi_issues.issueid' ] ,
            [ "schools" , 'wifi_issue_comments.school_id' , '=' , 'schools.id' ] ,
         ];

         // 获取数据
         $dataList = WifiIssueCommentsDao::getWifiIssueCommentsListInfo (
            $condition , [ 'wifi_issue_comments.commentid' , 'desc' ] ,
            [ 'page' => $param[ 'page' ] , 'limit' => self::$manger_wifi_page_limit ] ,
            $fieldArr , $joinArr
         );

         // 返回数据
         $this->dataForView[ 'dataList' ]            = $dataList;

         return view ( 'manager_wifi.wifiIssueComment.list' , $this->dataForView );

      }


      /**
       * Func 报修评论详情
       * @param WifiOrderRequest $request
       * @return view
       */
      public function detail(WifiIssueCommentRequest $request)
      {
         $param = $request->only ( [ 'commentid' ] );

         // 查询条件
         $condition[] = [ 'wifi_issue_comments.commentid' , '=' , $param['commentid'] ];

         // 获取字段
         $fieldArr = [
            'wifi_issue_comments.*' ,  'schools.name as schools_name' , 'campuses.name as campuses_name',
         ];

         // 关联表信息
         $joinArr = [
            [ "schools" , 'wifi_issue_comments.school_id' , '=' , 'schools.id' ] ,
            [ "campuses" , 'wifi_issue_comments.campus_id' , '=' , 'campuses.id' ] ,
         ];

         // 获取数据
         $dataOne['getWifiIssueCommentsOneInfo'] = WifiIssueCommentsDao::getWifiIssueCommentsOneInfo (
            $condition , [ 'commentid' , 'desc' ] ,
            $fieldArr , $joinArr
         );

         // 获取其他信息
         if ( ! empty( $dataOne['getWifiIssueCommentsOneInfo'] ) && $dataOne['getWifiIssueCommentsOneInfo']->issueid )
         {
            // 获取用户信息
            $dataOne['getUsersOneInfo'] = UsersDao::getUsersOneInfo (
               [ [ 'id' , '=' , $dataOne['getWifiIssueCommentsOneInfo']->user_id ] ] , [ 'id' , 'desc' ] , [ '*' ]
            );
            // 获取报修信息
            $dataOne['getWifiIssuesOneInfo'] = WifiIssues::getWifiIssuesOneInfo (
               [ [ 'issueid' , '=' , $dataOne['getWifiIssueCommentsOneInfo']->issueid ] ] , [ 'issueid' , 'desc' ] , [ '*' ]
            );
            // 获取处理信息
            $dataOne['getWifiIssueDisposesOneInfo'] = WifiIssueDisposesDao::getWifiIssueDisposesOneInfo (
               [ [ 'issueid' , '=' , $dataOne['getWifiIssueCommentsOneInfo']->issueid ] ] , [ 'disposeid' , 'desc' ] , [ '*' ]
            );
         }

         // 返回数据
         $this->dataForView[ 'dataOne' ] = $dataOne;

         return view ( 'manager_wifi.wifiIssueComment.detail' , $this->dataForView );
      }
   }