<?php
   namespace App\Http\Controllers\Operator\Affiche;

   use App\Dao\Schools\SchoolDao;
   use App\Dao\Affiche\Backstage\AfficheDao;

   use Illuminate\Http\Request;
   use App\Utils\FlashMessageBuilder;
   use App\Http\Controllers\Controller;
   use Illuminate\Support\Facades\Cache;
class AfficheController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
     * Func
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
        if($AfficheObj->editAffichesInfo($saveData,$dataOne->icheid))
        {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::SUCCESS , '操作成功');
            return redirect()->route('manager_affiche.affiche.affiche_pending_list');
        } else {
            FlashMessageBuilder::Push ( $request , FlashMessageBuilder::DANGER , '操作失败,请稍后重试');
            return redirect()->route('manager_affiche.affiche.affiche_pending_list');
        }
    }
}
