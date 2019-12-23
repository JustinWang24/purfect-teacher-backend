<?php

namespace App\Http\Controllers\Teacher;

use App\Dao\Pipeline\FlowDao;
use App\Http\Controllers\Controller;
use App\Utils\JsonBuilder;
use App\Http\Requests\OfficialDocument\ProcessRequest;
use App\Dao\OfficialDocuments\PresetStepDao;
use App\Dao\OfficialDocuments\ProgressDao;
use App\Dao\OfficialDocuments\ProgressStepsDao;
use App\Dao\OfficialDocuments\ProgressStepsUserDao;
use App\Utils\Pipeline\IFlow;

class OfficialDocumentController extends Controller
{
    /**
     * @param ProcessRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listAll(ProcessRequest $request){
        $this->dataForView['pageTitle'] = '公文管理';
        $dao = new FlowDao();
        $result = $dao->getGroupedFlows($request->getSchoolId(),[IFlow::TYPE_4]);
        $this->dataForView['list'] = $result[0]['flows'];
        return view('teacher.documents.list', $this->dataForView);
    }

    /**
     *系统预置步骤
     */
    public function presetStep()
    {
        $dao = new PresetStepDao;
        $field = ['id', 'name', 'describe', 'level'];
        $data = $dao->getAllStep($field);
        return JsonBuilder::Success($data);
    }

    /**
     * 生成公文流程
     * @param ProcessRequest $request
     * @return string
     */
    public  function  productionProcess(ProcessRequest $request)
    {
        $data['name']           = $request->get('name');
        $data['school_id']      = $request->getSchoolId();
        $data['preset_step_id'] = $request->get('process_data');

        $dao = new ProgressDao;

        $result = $dao->createProcess($data);
        if ($result) {
            return JsonBuilder::Success('流程添加成功', ['progress_id' => $result]);
        } else {
            return JsonBuilder::Error('流程添加失败');
        }
    }

    /**
     * 查询已经生成的公文流程的名称
     * @param ProcessRequest $request
     * @return string
     */
    public function getProcess(ProcessRequest $request)
    {
        $dao = new ProgressDao;

        $field = ['id', 'name'];
        $result = $dao->getProgressBySchoolId($request->getSchoolId(), $field);
        return JsonBuilder::Success($result);
    }

    /**
     * 查询一条公文流程的详情
     * @param ProcessRequest $request
     * @return string
     */
    public function getProcessDetails(ProcessRequest $request)
    {
        $dao = new ProgressStepsDao;

        $result = $dao->getOneProgressDetailsByProgressId($request->get('progress_id'));

        return JsonBuilder::Success($result);
    }

    /**
     * 添加步骤负责人
     * @param ProcessRequest $request
     * @return string
     */
    public function addStepUser(ProcessRequest $request)
    {
        $dao = new ProgressStepsUserDao;
        $result = $dao->createStep($request->get('step_user'));
        if ($result) {
            return JsonBuilder::Success('添加步骤负责人成功');
        } else {
            return JsonBuilder::Error('添加步骤负责人失败');
        }
    }

    /**
     * 修改步骤负责人
     * @param ProcessRequest $request
     * @return string
     */
    public function updateStepUser(ProcessRequest $request)
    {
        $data = $request->get('step_user');
        $where = $request->get('id');

        $dao = new ProgressStepsUserDao;
        $result = $dao->updateStep($data, $where);
        if ($result) {
            return JsonBuilder::Success('修改步骤负责人成功');
        } else {
            return JsonBuilder::Error('修改步骤负责人失败');
        }
    }
}
