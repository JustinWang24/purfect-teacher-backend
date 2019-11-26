<?php

namespace App\Http\Controllers\Operator\Applications;

use App\Utils\FlashMessageBuilder;
use App\Http\Controllers\Controller;
use App\Dao\Students\ApplicationTypeDao;
use App\Http\Requests\School\ApplicationRequest;

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
        } else {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,$result->getMessage());
        }
        return redirect()->route('school_manager.students.applications-set');
    }


    public function edit(ApplicationRequest $request) {
        $id = $request->get('id');
        $dao = new ApplicationTypeDao();
        $this->dataForView['type'] = $dao->getApplicationById($id);
        return view('school_manager.application_type.edit', $this->dataForView);
    }
}
