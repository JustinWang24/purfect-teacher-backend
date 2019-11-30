<?php

namespace App\Http\Controllers\Operator\Applications;

use App\Utils\FlashMessageBuilder;
use App\Http\Controllers\Controller;
use App\Dao\Students\ApplicationTypeDao;
use App\Http\Requests\School\ApplicationRequest;
use App\Utils\JsonBuilder;

class ApplicationTypeController extends Controller
{
    public function list(ApplicationRequest $request) {
        $schoolId = $request->getSchoolId();
        $dao = new ApplicationTypeDao();
        $this->dataForView['list'] = $dao->getTypeBySchoolId($schoolId);
        return view('school_manager.application_type.list', $this->dataForView);
    }


    public function add() {
        return view('school_manager.application_type.add', $this->dataForView);
    }

    /**
     * 保存申请类型
     *
     * @param ApplicationRequest $request
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function save(ApplicationRequest $request) {
        $data = $request->getApplicationTypeFormData();
        $data['school_id'] = $request->getSchoolId();
        $dao = new ApplicationTypeDao();
        if(!empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            $result = $dao->updateById($id, $data);
        } else {

            $result = $dao->create($data);
        }
        if($result->isSuccess()) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,$result->getMessage());

            if($request->ajax()){
                return JsonBuilder::Success();
            }
            else{
                return redirect()->route('school_manager.students.applications-set');
            }
        } else {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,$result->getMessage());
            if($request->ajax()){
                return JsonBuilder::Error($result->getMessage());
            }
            else{
                return redirect()->route('school_manager.students.applications-set');
            }
        }
    }


    public function edit(ApplicationRequest $request) {
        $id = $request->get('id');
        $dao = new ApplicationTypeDao();
        $this->dataForView['type'] = $dao->getApplicationById($id);
        return view('school_manager.application_type.edit', $this->dataForView);
    }
}
