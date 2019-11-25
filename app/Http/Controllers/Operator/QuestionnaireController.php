<?php


namespace App\Http\Controllers\Operator;


use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Models\Questionnaire\Questionnaire;
use App\Utils\FlashMessageBuilder;
use App\Utils\JsonBuilder;

class QuestionnaireController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 管理问卷调查表的 action
     * @param MyStandardRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function management(MyStandardRequest $request){
        // 获取学校
        $applications = (new Questionnaire())->getPaginatedApplications($request->getSchoolId());
        $this->dataForView['applications'] = $applications;
        $this->dataForView['pageTitle'] = '问卷调查表';
        return view('school_manager.questionnaire.list', $this->dataForView);
    }

    /**
     * 加载添加问卷的表单
     */
    public function add(){
        $this->dataForView['questionnaire'] = new Questionnaire();
        return view('school_manager.questionnaire.add', $this->dataForView);
    }


    /**
     * 保存问卷的方法
     * @param QuestionnaireRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(QuestionnaireRequest $request){
        $questionnaireData = $request->get('questionnaire');
        $questionnaireData['school_id'] = $request->session()->get('school.id');
        $dao = new Questionnaire($request->user());

        if(isset($questionnaireData['id'])){
            $result = $dao->update($questionnaireData);
        }
        else{
            $result = $dao->create($questionnaireData);
        }

        if($result){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,$questionnaireData['title'].'问卷保存成功');
        }else{
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'无法保存问卷'.$questionnaireData['title']);
        }
        return redirect()->route('school_manager.questionnaire.view');
    }
}
