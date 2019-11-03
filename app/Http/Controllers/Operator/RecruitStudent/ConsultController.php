<?php
namespace App\Http\Controllers\Operator\RecruitStudent;

use App\Utils\FlashMessageBuilder;
use App\Dao\RecruitStudent\ConsultDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecruitStudent\ConsultRequest;

class ConsultController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function list(ConsultRequest $request) {
        $schoolId = $request->getSchoolId();
        $consultDao = new ConsultDao();
        $result = $consultDao->getConsultPage($schoolId);
        $this->dataForView['consult'] = $result;
        return view('school_manager.recruitStudent.consult.list', $this->dataForView);

    }


    public function add(ConsultRequest $request) {

        if($request->isMethod('post')) {
            $user = $request->user();
            $all = $request->post('consult');
            $all['school_id'] = $request->getSchoolId();
            $consultDao = new ConsultDao($user);
            $result = $consultDao->saveConsult($all);
            if($result) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'添加成功');
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'添加失败');
            }
            return redirect()->route('school_manager.consult.list');
        }
        return view('school_manager.recruitStudent.consult.add', $this->dataForView);

    }


    public function edit(ConsultRequest $request) {
        $user = $request->user();
        $consultDao = new ConsultDao($user);
        if($request->isMethod('post')) {
            $all = $request->post('consult');
            $result = $consultDao->saveConsult($all);
            if($result) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'编辑成功');
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'编辑失败');
            }
            return redirect()->route('school_manager.consult.list');
        }

        $id = $request->get('id');
        $info = $consultDao->getConsultById($id);
        $this->dataForView['consult'] = $info;
        return view('school_manager.recruitStudent.consult.edit', $this->dataForView);

    }

    public function delete(ConsultRequest $request) {
        $id = $request->get('id');
        $consultDao = new ConsultDao();
        $re = $consultDao->delete($id);
        if($re) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'删除成功');
        } else {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'编辑失败');
        }
        return redirect()->route('school_manager.consult.list');
    }
}
