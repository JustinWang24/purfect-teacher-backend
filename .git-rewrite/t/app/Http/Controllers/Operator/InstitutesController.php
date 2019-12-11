<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 10:48 PM
 */

namespace App\Http\Controllers\Operator;
use App\BusinessLogic\DepartmentsListPage\Factory;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\InstituteRequest;
use App\Dao\Schools\InstituteDao;
use App\Dao\Schools\CampusDao;
use App\Models\Schools\Institute;
use App\Utils\FlashMessageBuilder;

class InstitutesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 加载更新学院的表单视图
     * @param InstituteRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(InstituteRequest $request){
        $dao = new InstituteDao($request->user());
        $this->dataForView['institute'] = $dao->getInstituteById($request->uuid());
        return view('school_manager.institute.edit', $this->dataForView);
    }

    /**
     * 加载添加学院的表单视图
     * @param InstituteRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(InstituteRequest $request){
        $dao = new CampusDao($request->user());
        $this->dataForView['campus'] = $dao->getCampusById($request->uuid());
        $this->dataForView['institute'] = new Institute();
        return view('school_manager.institute.add', $this->dataForView);
    }

    /**
     * 保存学院的操作
     * @param InstituteRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(InstituteRequest $request){
        $majorData = $request->getFormData();
        $dao = new InstituteDao($request->user());
        $uuid = $majorData['campus_id'];

        if(isset($majorData['id'])){
            // 更新学院的操作
            if($dao->updateInstitute($majorData)){
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS, $majorData['name'].'专业已经修改成功');
            }else{
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, $majorData['name'].'专业修改失败, 请重新试一下');
            }
        }else{
            // 新增专业的操作
            if($dao->createInstitute($majorData)){
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS, $majorData['name'].'专业已经创建成功');
            }else{
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, $majorData['name'].'专业创建失败, 请重新试一下');
            }
        }
        return redirect()->route('school_manager.campus.institutes',['uuid'=>$uuid,'by'=>'campus']);
    }

    /**
     * 查看学院中系的列表
     * @param InstituteRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function departments(InstituteRequest $request){
        $logic = Factory::GetLogic($request);
        // 查看系的列表

        $this->dataForView['parent'] = $logic->getParentModel();
        $this->dataForView['departments'] = $logic->getData();
        $this->dataForView['appendedParams'] = $logic->getAppendedParams();

        return view($logic->getViewPath(), $this->dataForView);
    }
}