<?php
   namespace App\Http\Controllers\Operator\Affiche;

   use App\Dao\Affiche\CommonDao;
   use App\Dao\Schools\SchoolDao;
   use App\Dao\Affiche\Backstage\AfficheDao;

   use Illuminate\Http\Request;
   use App\Utils\FlashMessageBuilder;
   use App\Http\Controllers\Controller;
   use Illuminate\Support\Facades\Cache;
   use Illuminate\Support\Facades\DB;

   class AfficheController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

       // 动态分类
       private static $categroypidArr = array(0 => '其他', 1 => '活动');

    /**
     * Func 置顶列表
     * @param Request $request
     * @return view
     */
    public function top_affiche_list(Request $request)
    {
        $page = $request->input('page', 1);
        $keywords = $request->input('keywords', '');
        $school_id = $request->input('school_id', 0);
        $campus_id = $request->input('campus_id', 0);
        $status = $request->input('status', 0);

        // 关键词查询
        $param['keywords'] = $keywords;
        $param['school_id'] = $school_id;

        $AfficheObj = new AfficheDao();
        $dataList = $AfficheObj->getAfficheStickListInfo($param, $page);

        // 返回数据
        $this->dataForView['dataList'] = $dataList;

        return view('manager_affiche.affiche.top_affiche_list', $this->dataForView);
    }


    /**
     * Func 待审核列表
     * @param Request $request
     * @return view
     */
    public function affiche_pending_list(Request $request)
    {
        $page = $request->input('page', 1);
        $keywords = $request->input('keywords', '');
        $school_id = $request->input('school_id', 0);
        $campus_id = $request->input('campus_id', 0);
        $status = $request->input('status', 0);

        // 关键词查询
        $param['keywords'] = $keywords;
        $param['school_id'] = $school_id;
        $param['campus_id'] = $campus_id;
        $param['status'] = $status ? [$status] : [-1,2];

        $AfficheObj = new AfficheDao();
        $dataList = $AfficheObj->getAfficheListInfo($param, $page);

        // 返回数据
        $this->dataForView['dataList'] = $dataList;
        $this->dataForView['afficheStatusArr'] = AfficheDao::$afficheStatusArr;

        return view('manager_affiche.affiche.affiche_pending_list', $this->dataForView);
    }


    /**
     * Func 待审核列表
     * @param Request $request
     * @return view
     */
    public function affiche_adopt_list(Request $request)
    {
        $page = $request->input('page', 1);
        $keywords = $request->input('keywords', '');
        $school_id = $request->input('school_id', 0);
        $campus_id = $request->input('campus_id', 0);
        $status = $request->input('status', 0);

        // 关键词查询
        $param['keywords'] = $keywords;
        $param['school_id'] = $school_id;
        $param['campus_id'] = $campus_id;
        $param['status'] = $status ? [$status] : [1];

        $AfficheObj = new AfficheDao();
        $dataList = $AfficheObj->getAfficheListInfo($param, $page);

        // 返回数据
        $this->dataForView['dataList'] = $dataList;
        $this->dataForView['afficheStatusArr'] = AfficheDao::$afficheStatusArr;

        return view('manager_affiche.affiche.affiche_adopt_list', $this->dataForView);
    }

    /**
     * Func 动态详情
     * @param Request $request
     * @return view
     */
    public function affiche_one(Request $request)
    {
        $icheid = $request->input('icheid', 0);

        $AfficheObj = new AfficheDao();
        $dataOne = $AfficheObj->getAfficheOneInfo($icheid);

        // 返回数据
        $this->dataForView['dataOne'] = $dataOne;
        $this->dataForView['afficheTypeArr'] = AfficheDao::$afficheTypeArr;
        $this->dataForView['afficheStatusArr'] = AfficheDao::$afficheStatusArr;

        return view('manager_affiche.affiche.affiche_one', $this->dataForView);
    }

    /**
     * Func 审核
     * @param WifiRequest $request
     * @return view
     */
    public function affiche_check_one(Request $request)
    {
        $icheid = (Int)$request->input('icheid', 0);
        $status = (Int)$request->input('status', 0);
        $iche_categroypid = (Int)$request->input('iche_categroypid', 0);
        $iche_checkdesc = (String)$request->input('iche_checkdesc', '');

        if (!intval($icheid)) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '参数错误');
            return redirect()->route('manager_affiche.affiche.affiche_one',[ 'icheid' => $icheid ]);
        }
        if (!in_array(intval($status),[1,2])) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '请选择状态');
            return redirect()->route('manager_affiche.affiche.affiche_one',[ 'icheid' => $icheid ]);
        }
        if ($status == 2 && !$iche_checkdesc ) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '请填写审核原因');
            return redirect()->route('manager_affiche.affiche.affiche_one',[ 'icheid' => $icheid ]);
        }

        // 获取数据
        $AfficheObj = new AfficheDao();
        $dataOne = $AfficheObj->getAfficheOneInfo($icheid);

        if(!isset($dataOne->icheid))
        {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '参数错误');
            return redirect()->route('manager_affiche.affiche.affiche_one',[ 'icheid' => $icheid ]);
        }
        if($dataOne->status != -1)
        {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '状态已更新，请勿重复操作');
            return redirect()->route('manager_affiche.affiche.affiche_one',[ 'icheid' => $icheid ]);
        }

        // 更新状态值
        $saveData['status'] = $status;
        $saveData['iche_checktime'] = date('Y-m-d H:i:s');
        $saveData['iche_checkdesc'] = trim($iche_checkdesc);
        $saveData['houtai_operateid'] = 1;
        $saveData['houtai_operatename'] = '管理员';
        $saveData['iche_categroypid'] = $iche_categroypid;
        $saveData['houtai_operatename'] = self::$categroypidArr[$iche_categroypid];

        if ($AfficheObj->editAffichesInfo($saveData, $dataOne->icheid))
        {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::SUCCESS , '操作成功');
            return redirect()->route('manager_affiche.affiche.affiche_pending_list');
        } else {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '操作失败,请稍后重试');
            return redirect()->route('manager_affiche.affiche.affiche_pending_list');
        }
    }

    /**
     * Func 动态评论列表
     * @param Request $request
     * @return view
     */
    public function affiche_comment_list(Request $request)
    {
        $page = (Int)$request->input('page', 1);
        $keywords = (String)$request->input('keywords', '');
        $iche_id = (Int)$request->input('icheid', 0);

        if (!$iche_id) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '参数错误');
            return redirect()->route('/');
        }

        // 关键词查询
        $param['iche_id'] = $iche_id;
        $param['keywords'] = $keywords;
        $param['status'] = [1];

        $afficheObj = new AfficheDao();
        $dataList = $afficheObj->getAfficheCommentListInfo($param, $page);

        // 返回数据
        $this->dataForView['dataList'] = $dataList;
        $this->dataForView['statusArr'] = AfficheDao::$noticeStatusArr;

        return view('manager_affiche.affiche.affiche_comment_list', $this->dataForView);
    }

    /**
     * Func 动态点赞列表
     * @param Request $request
     * @return view
     */
    public function affiche_praise_list(Request $request)
    {
        $page = (Int)$request->input('page', 1);
        $keywords = (String)$request->input('keywords', '');
        $iche_id = (Int)$request->input('icheid', 0);

        if (!$iche_id) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '参数错误');
            return redirect()->route('/');
        }

        // 关键词查询
        $param['typeid'] = 1;
        $param['minx_id'] = $iche_id;
        $param['keywords'] = $keywords;

        $afficheObj = new AfficheDao();
        $dataList = $afficheObj->getAffichePraiseListInfo($param, $page);

        // 返回数据
        $this->dataForView['dataList'] = $dataList;
        $this->dataForView['statusArr'] = AfficheDao::$noticeStatusArr;

        return view('manager_affiche.affiche.affiche_praise_list', $this->dataForView);
    }

       /**
        * Func 动态浏览列表
        * @param Request $request
        * @return view
        */
       public function affiche_view_list(Request $request)
       {
           $page = (Int)$request->input('page', 1);
           $keywords = (String)$request->input('keywords', '');
           $iche_id = (Int)$request->input('icheid', 0);

           if (!$iche_id) {
               FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '参数错误');
               return redirect()->route('/');
           }

           // 关键词查询
           $param['iche_id'] = $iche_id;
           $param['keywords'] = $keywords;

           $afficheObj = new AfficheDao();
           $dataList = $afficheObj->getAfficheViewListInfo($param, $page);

           // 返回数据
           $this->dataForView['dataList'] = $dataList;

           return view('manager_affiche.affiche.affiche_view_list', $this->dataForView);
       }

    /**
     * Func 添加置顶
     * @param WifiRequest $request
     * @return view
     */
    public function top_affiche_add(Request $request)
    {
        // 提交数据保存
        if ( $request->isMethod ( 'post' ) )
        {
            $school_id = (Int)$request->input('school_id', 0);
            $stick_mixid = (Int)$request->input('stick_mixid', 0);

            if (!intval($stick_mixid)) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '请填写动态编号');
                return redirect()->route('manager_affiche.affiche.top_affiche_add');
            }

            // 获取数据
            $AfficheObj = new AfficheDao();
            $dataOne = $AfficheObj->getAfficheOneInfo($stick_mixid);

            if(!isset($dataOne->icheid))
            {
                FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '编号错误');
                return redirect()->route('manager_affiche.affiche.top_affiche_add');
            }

            if ($dataOne['status'] != 1) {
                FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '编号错误');
                return redirect()->route('manager_affiche.affiche.top_affiche_add');
            }

            // 添加数据
            $addData['status'] = 1;
            $addData['stick_posit'] = 1;
            $addData['school_id'] = $school_id;
            $addData['stick_mixid'] = $dataOne['icheid'];
            $addData['stick_title'] = $dataOne['iche_content'];

            if ($AfficheObj->addAfficheStickInfo($addData)) {
                FlashMessageBuilder::Push ( $request , FlashMessageBuilder::SUCCESS , '操作成功');
                return redirect()->route('manager_affiche.affiche.top_affiche_list');
            } else {
                FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '操作失败,请稍后重试');
                return redirect()->route('manager_affiche.affiche.top_affiche_add');
            }
        }

        // 返回数据
        $this->dataForView['dataList'] = [];

        return view('manager_affiche.affiche.top_affiche_add', $this->dataForView);
    }

    /**
     * Func 删除置顶
     * @param WifiRequest $request
     * @return view
     */
    public function top_affiche_delete(Request $request)
    {

        $stickid = (Int)$request->input('stickid', 0);

        if (!intval($stickid)) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '请填写动态编号');
            return redirect()->route('manager_affiche.affiche.top_affiche_list');
        }

        // 获取数据
        $AfficheObj = new AfficheDao();
        if($AfficheObj->deleteAfficheStickInfo($stickid)){
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::SUCCESS , '删除成功');
            return redirect()->route('manager_affiche.affiche.top_affiche_list');
        } else {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '操作失败,请稍后重试');
            return redirect()->route('manager_affiche.affiche.top_affiche_list');
        }
    }

    /**
     * Func 删除排序
     * @param WifiRequest $request
     * @return view
     */
    public function top_affiche_sort(Request $request)
    {
        $stickid = (Int)$request->input('stickid', 0);
        $stick_order = (Int)$request->input('stick_order', 1000);

        if (intval($stickid) && intval($stick_order)) {

            (new AfficheDao())->editAfficheStickInfo(['stick_order'=>$stick_order],$stickid);
        }
    }
}
