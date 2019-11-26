<?php


namespace App\Http\Controllers\Operator\Contents;


use App\Dao\Contents\ScienceDao;
use App\Utils\FlashMessageBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contents\ScienceRequest;

class ScienceController extends Controller
{

    public function list(ScienceRequest $request) {
        $schoolId = $request->getSchoolId();
        $dao = new ScienceDao();
        $list = $dao->getScienceListBySchoolId($schoolId);
        $this->dataForView['list'] = $list;
        return view('school_manager.science.list', $this->dataForView);
    }


    public function create(ScienceRequest $request) {
        if($request->isMethod('post')) {
            $data = $request->getFormData();
            $dao = new ScienceDao();
            $result = $dao->create($data);
            if($result) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'创建成功');
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'创建失败');
            }

            return redirect()->route('school_manager.contents.science.list');

        }

        $this->dataForView['js'][] = 'school_manager.banner.custom_js';
        return view('school_manager.science.add', $this->dataForView);
    }


    public function edit(ScienceRequest $request) {
        $dao = new ScienceDao();
        if($request->isMethod('post')) {
            $data = $request->getFormData();
            $id = $data['id'];
            unset($data['id']);
            $result = $dao->updateScienceById($id, $data);
            if($result) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'编辑成功');
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'编辑失败');
            }

            return redirect()->route('school_manager.contents.science.list');
        }
        $id = $request->get('id');

        $info =  $dao->getScienceById($id);
        $this->dataForView['science'] = $info;
        $this->dataForView['js'][] = 'school_manager.banner.custom_js';
        return view('school_manager.science.edit', $this->dataForView);
    }


    public function delete(ScienceRequest $request) {
        $id = $request->get('id');
        $dao = new ScienceDao();
        $result = $dao->delete($id);
        if($result) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'删除成功');
        } else {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'删除失败');
        }

        return redirect()->route('school_manager.contents.science.list');
    }

}
