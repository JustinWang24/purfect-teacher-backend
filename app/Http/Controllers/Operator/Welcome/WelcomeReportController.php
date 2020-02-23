<?php
   namespace App\Http\Controllers\Operator\Welcome;

   use App\Dao\Schools\SchoolDao;
   use App\Dao\Welcome\Backstage\WelcomeUserReportDao;
   use App\Models\Welcome\WelcomeUserReportsProject;

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
          $dataList = $welcomeUserReportObj->getWelcomeUserReportListInfo($school->id, [2], $page);

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
           $dataList = $welcomeUserReportObj->getWelcomeUserReportListInfo($school->id, [1], $page);

           $this->dataForView['dataList'] = $dataList;

           return view('welcome_manager.welcomeReport.processing_list', $this->dataForView);
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
           $dataList = $welcomeUserReportObj->getWelcomeUserReportListInfo($school->id, [3], $page);

           $this->dataForView['dataList'] = $dataList;

           return view('welcome_manager.welcomeReport.completed_list', $this->dataForView);
       }

       /**
        * Func 学费列表
        * @param Request $request
        * @return view
        */
       public function tuitionfee_list(Request $request)
       {
           $page = $request->input('page', 1);
           $uuid = $request->input('uuid', '');
           $isfee = $request->input('isfee', 1);
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
           $dataList = $welcomeUserReportObj->getUserReportsOrProjectsListInfo($school->id, [1, 3], 1, $isfee, $page);

           $this->dataForView['dataList'] = $dataList;

           return view('welcome_manager.welcomeReport.tuitionfee_list', $this->dataForView);
       }

       /**
        * Func 书费列表
        * @param Request $request
        * @return view
        */
       public function bookfee_list(Request $request)
       {
           $page = $request->input('page', 1);
           $uuid = $request->input('uuid', '');
           $isfee = $request->input('isfee', 1);
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
           $dataList = $welcomeUserReportObj->getUserReportsOrProjectsListInfo($school->id, [1, 3], 2, $isfee, $page);

           $this->dataForView['dataList'] = $dataList;

           return view('welcome_manager.welcomeReport.bookfee_list', $this->dataForView);
       }

       /**
        * Func 住宿费
        * @param Request $request
        * @return view
        */
       public function roomfee_list(Request $request)
       {
           $page = $request->input('page', 1);
           $uuid = $request->input('uuid', '');
           $isfee = $request->input('isfee', 1);
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
           $dataList = $welcomeUserReportObj->getUserReportsOrProjectsListInfo($school->id, [1, 3], 2, $isfee, $page);

           $this->dataForView['dataList'] = $dataList;

           return view('welcome_manager.welcomeReport.roomfee_list', $this->dataForView);
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

       /**
        * Func 提交待处理报到
        * @param Request $request
        * @return view
        */
       public function wait_update(Request $request)
       {
           $uuid = (String)$request->input('uuid', '');
           $typeidArr = $request->input('typeid', '');
           $typeidArr = array_filter(array_unique($typeidArr));

           if (!intval($uuid)) {
               FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '参数错误');
               return redirect()->route('welcome_manager.welcomeReport.detail',[ 'uuid' => $uuid ]);
           }
           if (!is_array($typeidArr) && count($typeidArr) <= 0) {
               FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '请选择提交的资料');
               return redirect()->route('welcome_manager.welcomeReport.detail', ['uuid' => $uuid]);
           }

           // 获取数据
           $welcomeUserReportObj = new WelcomeUserReportDao();
           $dataOne = $welcomeUserReportObj->getWelcomeUserReportOneInfo($uuid);
           if (empty($dataOne)) {
               FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '参数错误');
               return redirect()->route('welcome_manager.welcomeReport.detail', ['uuid' => $uuid]);
           }
           if ($dataOne['status'] != 2) {
               FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '数据已处理');
               return redirect()->route('welcome_manager.welcomeReport.detail', ['uuid' => $uuid]);
           }

           foreach ($typeidArr as $val)
           {
               // 更新状态值
               $addData[] = [
                   'uuid' => sha1(time()),
                   'typeid' => $val,
                   'user_id' => $dataOne['user_id'],
                   'school_id' => $dataOne['school_id'],
                   'campus_id' => $dataOne['campus_id'],
                   'created_at' => date('Y-m-d H:i:s'),
                   'project_title' => WelcomeUserReportDao::$reportProjectArr[1][$val],
                   'project_desc' => WelcomeUserReportDao::$reportProjectArr[1][$val]
               ];
           }
           if (WelcomeUserReportsProject::insert($addData)) {

               // 更新状态
               $welcomeUserReportObj = new WelcomeUserReportDao();
               $welcomeUserReportObj->editWelcomeUserReportsInfo(['status'=>1,'complete_date1'=>date('Y-m-d H:i:s')],$dataOne['configid']);

               $schoolObj = new SchoolDao();
               $school = $schoolObj->getSchoolByIdOrUuid($dataOne['school_id']);
               FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS, '操作成功');
               return redirect()->route('welcome_manager.welcomeReport.wait_list', ['uuid' => $school['uuid']]);
           } else {
               FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '操作失败,请稍后重试');
               return redirect()->route('welcome_manager.welcomeReport.detail', ['uuid' => $uuid]);
           }
       }

   }
