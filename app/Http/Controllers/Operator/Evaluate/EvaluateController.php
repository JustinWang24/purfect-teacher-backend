<?php


namespace App\Http\Controllers\Operator\Evaluate;

use App\Dao\Evaluate\EvaluateDao;
use App\Dao\Evaluate\EvaluateStudentTitleDao;
use App\Utils\FlashMessageBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Evaluate\EvaluateRequest;

class EvaluateController extends Controller
{

    public function list(EvaluateRequest $request)
    {
        $schoolId                  = $request->getSchoolId();
        $dao                       = new EvaluateDao();
        $list                      = $dao->pageList($schoolId);
        $this->dataForView['list'] = $list;
        return view('school_manager.evaluate.content.list', $this->dataForView);
    }


    /**
     * 创建评教内容
     * @param EvaluateRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(EvaluateRequest $request)
    {
        if ($request->isMethod('post')) {
            $data   = $request->getFormDate();
            $dao    = new EvaluateDao();
            $result = $dao->create($data);
            $msg    = $result->getMessage();
            if ($result->isSuccess()) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS, $msg);
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, $msg);
            }
            return redirect()->route('school_manager.evaluate.content-list');
        }

        return view('school_manager.evaluate.content.create', $this->dataForView);

    }


    /**
     * 编辑
     * @param EvaluateRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(EvaluateRequest $request)
    {
        $dao = new EvaluateDao();
        if ($request->isMethod('post')) {
            $data   = $request->getFormDate();
            $result = $dao->editEvaluateById($data['id'], $data);
            if ($result) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS, '保存成功');
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '保存失败');
            }
            return redirect()->route('school_manager.evaluate.content-list');
        }
        $id                            = $request->get('id');
        $info                          = $dao->getEvaluateById($id);
        $this->dataForView['evaluate'] = $info;
        return view('school_manager.evaluate.content.edit', $this->dataForView);
    }


    /**
     * 删除
     * @param EvaluateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(EvaluateRequest $request)
    {
        $id     = $request->get('id');
        $dao    = new EvaluateDao();
        $result = $dao->deleteEvaluate($id);
        if ($result) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS, '删除成功');
        } else {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '删除失败');
        }
        return redirect()->route('school_manager.evaluate.content-list');
    }

    /**
     * 评学列表
     * @param EvaluateRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function evaluateStudentList(EvaluateRequest $request)
    {
        $schoolId = $request->getSchoolId();

        $dao  = new EvaluateStudentTitleDao;
        $data = $dao->getEvaluateTitlePageBySchoolId($schoolId);
        $this->dataForView['data'] = $data;
        return view('school_manager.evaluate.', $this->dataForView);
    }

}
