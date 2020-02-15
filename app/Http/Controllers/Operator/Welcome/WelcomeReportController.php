<?php
   namespace App\Http\Controllers\Operator\Welcome;

   use App\Dao\Schools\SchoolDao;
   use App\Dao\Welcome\Backstage\WelcomeUserReportDao;


   use App\Dao\Wifi\Backstage\UsersDao; // 用户
   use App\Dao\Wifi\Backstage\WifiIssueDao; // 报修信息
   use App\Dao\Wifi\Backstage\WifiIssueDisposesDao; // 报修处理
   use App\Dao\Wifi\Backstage\WifiIssueCommentsDao; // 报修评论

   use Illuminate\Http\Request;
   use App\Utils\FlashMessageBuilder;
   use App\Http\Controllers\Controller;
   use Illuminate\Support\Facades\Cache;
   class WelcomeReportController extends Controller
   {
       // 跳转路径
       private static $redirectUrl = 'home';

       public function __construct()
       {
           $this->middleware('auth');
       }

       /**
        * Func 待报到列表
        * @param Request $request
        * @return view
        */
      public function wait_list(Request $request)
      {
          $page = $request->input('page', 1);
          $uuid = $request->input('uuid', '');
          $keywords = $request->input('keywords', '');

          if (!trim($uuid)) {
              return redirect(self::$redirectUrl);
          }

          // 获取学校信息
          $schoolObj = new SchoolDao();
          $school = $schoolObj->getSchoolByIdOrUuid($uuid);
          if (empty($school)) {
              return redirect(self::$redirectUrl);
          }

          // 列表
          $welcomeUserReportObj = new WelcomeUserReportDao();
          $dataList = $welcomeUserReportObj->getWelcomeUserReportListInfo($school->id, 2, $page);

          $this->dataForView['dataList'] = $dataList;
          return view('welcome_manager.welcomeReport.wait_list', $this->dataForView);
      }

       /**
        * Func 报到中列表
        * @param Request $request
        * @return view
        */
       public function processing_list(Request $request)
       {
           $page = $request->input('page', 1);
           $uuid = $request->input('uuid', '');
           $keywords = $request->input('keywords', '');

           if (!trim($uuid)) {
               return redirect(self::$redirectUrl);
           }

           // 获取学校信息
           $schoolObj = new SchoolDao();
           $school = $schoolObj->getSchoolByIdOrUuid($uuid);
           if (empty($school)) {
               return redirect(self::$redirectUrl);
           }

           // 列表
           $welcomeUserReportObj = new WelcomeUserReportDao();
           $dataList = $welcomeUserReportObj->getWelcomeUserReportListInfo($school->id, 1, $page);
           return view('welcome_manager.welcomeReport.wait_list', $this->dataForView);
       }

       /**
        * Func 已报到列表
        * @param Request $request
        * @return view
        */
       public function completed_list(Request $request)
       {
           $page = $request->input('page', 1);
           $uuid = $request->input('uuid', '');
           $keywords = $request->input('keywords', '');

           if (!trim($uuid)) {
               return redirect(self::$redirectUrl);
           }

           // 获取学校信息
           $schoolObj = new SchoolDao();
           $school = $schoolObj->getSchoolByIdOrUuid($uuid);
           if (empty($school)) {
               return redirect(self::$redirectUrl);
           }

           // 列表
           $welcomeUserReportObj = new WelcomeUserReportDao();
           $dataList = $welcomeUserReportObj->getWelcomeUserReportListInfo($school->id, 3, $page);

           return view('welcome_manager.welcomeReport.wait_list', $this->dataForView);
       }

       /**
        * Func 详情
        * @param Request $request
        * @return view
        */
       public function detail(Request $request)
       {
           $uuid = $request->input('uuid', '');

           if (!trim($uuid)) {
               return redirect(self::$redirectUrl);
           }
           // 详情
           $welcomeUserReportObj = new WelcomeUserReportDao();
           $dataOne = $welcomeUserReportObj->getWelcomeUserReportOneInfo($uuid);
           if (!empty($dataOne)) {
               $dataOne['info1'] = json_decode($dataOne['steps_1_str'], true);
               $dataOne['info2'] = json_decode($dataOne['steps_2_str'], true);
               unset($dataOne['steps_1_str'], $dataOne['steps_2_str']);
           }

           $this->dataForView['dataOne'] = $dataOne;
           $this->dataForView['reportPicsArr'] = WelcomeUserReportDao::$reportPicsArr;
           $this->dataForView['reportStatusArr'] = WelcomeUserReportDao::$reportStatusArr;
           $this->dataForView['reportProjectArr'] = WelcomeUserReportDao::$reportProjectArr[1];

           return view('welcome_manager.welcomeReport.detail', $this->dataForView);
       }

   }
