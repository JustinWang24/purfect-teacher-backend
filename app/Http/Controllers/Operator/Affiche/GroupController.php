<?php
   namespace App\Http\Controllers\Operator\Affiche;

   use App\Dao\Schools\SchoolDao;
   use App\Dao\Affiche\Backstage\AfficheDao;
   use App\Dao\Affiche\Backstage\CollegeGroupDao;
   use App\Dao\Affiche\Backstage\CollegeGroupPicsDao;
   use App\Dao\Affiche\Backstage\CollegeGroupJoinDao;
   use App\Dao\Affiche\Backstage\CollegeGroupNoticesDao;
   use App\Dao\Affiche\Backstage\CollegeGroupNoticeReaderDao;

   use Illuminate\Http\Request;
   use App\Utils\FlashMessageBuilder;
   use App\Http\Controllers\Controller;
   use Illuminate\Support\Facades\Cache;
   class GroupController extends Controller
   {
       // 跳转路径
       private static $redirectUrl = 'home';

       public function __construct()
       {
           $this->middleware('auth');
       }

       /**
        * Func 待审核列表
        * @param Request $request
        * @return view
        */
       public function group_pending_list(Request $request)
       {
           $page = $request->input('page', 1);
           $keywords = $request->input('keywords', '');
           $school_id = $request->input('school_id', 0);
           $group_typeid = $request->input('group_typeid', 0);
           $status = $request->input('status', 0);

           // 关键词查询
           $param['keywords'] = $keywords;
           $param['school_id'] = $school_id;
           $param['group_typeid'] = $group_typeid;
           $param['status'] = $status ? [$status] : [-1,2];

           $collegeGroupObj = new CollegeGroupDao();
           $dataList = $collegeGroupObj->getCollegeGroupListInfo($param, $page);

           // 返回数据
           $this->dataForView['dataList'] = $dataList;
           $this->dataForView['groupStatusArr'] = CollegeGroupDao::$groupStatusArr;
           $this->dataForView['groupTypeIdArr'] = CollegeGroupDao::$groupTypeIdArr;

           return view('manager_affiche.group.group_pending_list', $this->dataForView);
       }

       /**
        * Func 已通过列表
        * @param Request $request
        * @return view
        */
       /**
        * Func 待审核列表
        * @param Request $request
        * @return view
        */
       public function group_adopt_list(Request $request)
       {
           $page = $request->input('page', 1);
           $keywords = $request->input('keywords', '');
           $school_id = $request->input('school_id', 0);
           $group_typeid = $request->input('group_typeid', 0);

           // 关键词查询
           $param['keywords'] = $keywords;
           $param['school_id'] = $school_id;
           $param['group_typeid'] = $group_typeid;
           $param['status'] = 1;

           $collegeGroupObj = new CollegeGroupDao();
           $dataList = $collegeGroupObj->getCollegeGroupListInfo($param, $page);

           // 返回数据
           $this->dataForView['dataList'] = $dataList;
           $this->dataForView['groupStatusArr'] = CollegeGroupDao::$groupStatusArr;
           $this->dataForView['groupTypeIdArr'] = CollegeGroupDao::$groupTypeIdArr;

           return view('manager_affiche.group.group_adopt_list', $this->dataForView);
       }

       /**
        * Func 组织详情
        * @param Request $request
        * @return view
        */
       public function group_one(Request $request)
       {
           $groupid = $request->input('groupid', 0);

           if (!intval($groupid)) {
               FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '参数错误');
           }

           $collegeGroupObj = new CollegeGroupDao();
           $dataOne = $collegeGroupObj->getCollegeGroupOneInfo($groupid);

           // 返回数据
           $this->dataForView['dataOne'] = $dataOne;
           $this->dataForView['groupStatusArr'] = CollegeGroupDao::$groupStatusArr;
           $this->dataForView['groupTypeIdArr'] = CollegeGroupDao::$groupTypeIdArr;

           return view('manager_affiche.group.group_one', $this->dataForView);
       }

       /**
        * Func 审核
        * @param WifiRequest $request
        * @return view
        */
       public function group_check_one(Request $request)
       {
           $groupid = (Int)$request->input('groupid', 0);
           $status = (Int)$request->input('status', 0);
           $authu_refusedesc = (String)$request->input('authu_refusedesc', '');

           if (!$groupid) {
               FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '参数错误');
               return redirect()->route('manager_affiche.group.group_one',[ 'groupid' => $groupid ]);
           }
           if (!in_array($status,[1,2])) {
               FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '请选择状态');
               return redirect()->route('manager_affiche.group.group_one',[ 'groupid' => $groupid ]);
           }
           if ($status == 2 && !$authu_refusedesc ) {
               FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '请填写审核原因');
               return redirect()->route('manager_affiche.group.group_one',[ 'groupid' => $groupid ]);
           }

           // 获取数据
           $collegeGroupObj = new CollegeGroupDao();
           $dataOne = $collegeGroupObj->getCollegeGroupOneInfo($groupid);

           if(!isset($dataOne->groupid))
           {
               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '参数错误');
               return redirect()->route('manager_affiche.group.group_one',[ 'groupid' => $groupid ]);
           }
           if($dataOne->status != -1)
           {
               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '状态已更新，请勿重复操作');
               return redirect()->route('manager_affiche.group.group_one',[ 'groupid' => $groupid ]);
           }

           // 更新状态值
           $saveData['status'] = $status;
           $saveData['group_time1'] = date('Y-m-d H:i:s');
           $saveData['authu_refusedesc'] = trim($authu_refusedesc);
           // TODO.....
           $saveData['houtai_operateid'] = 1;
           $saveData['houtai_operatename'] = '管理员';
           if($collegeGroupObj->editCollegegroupOneInfo($saveData,$dataOne->groupid))
           {
               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::SUCCESS , '操作成功');
               return redirect()->route('manager_affiche.group.group_pending_list');
           } else {
               FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '操作失败,请稍后重试');
               return redirect()->route('manager_affiche.group.group_pending_list');
           }
       }
   }
