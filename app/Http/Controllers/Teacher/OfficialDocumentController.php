<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Utils\JsonBuilder;
use App\Http\Requests\OfficialDocument\ProcessRequest;
use App\Dao\OfficialDocuments\PresetStepDao;
use App\Dao\OfficialDocuments\ProgressDao;
use App\Dao\OfficialDocuments\ProgressStepsDao;

class OfficialDocumentController extends Controller
{

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
        $data['preset_step_id'] = $request->get('preset_step_id');

        $dao = new ProgressDao;

        $result = $dao->createProcess($data);
        if ($result) {
            return JsonBuilder::Success('流程添加成功', ['progress_id' => $result]);
        } else {
            return JsonBuilder::Success('流程添加失败');
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
     */
    public function getProcessDetails(ProcessRequest $request)
    {
        $progressId = $request->get('progress_id');

        $dao = new ProgressStepsDao;

        $result = $dao->getOneProgressDetailsByProgressId(1);
    }



}
