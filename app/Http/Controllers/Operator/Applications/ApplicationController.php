<?php
namespace App\Http\Controllers\Operator\Applications;

use App\Utils\FlashMessageBuilder;
use App\Dao\Students\ApplicationDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\ApplicationRequest;

class ApplicationController extends Controller
{
    public function list(ApplicationRequest $request) {
        $dao = new ApplicationDao();
        $schoolId = $request->getSchoolId();
        $this->dataForView['list'] = $dao->getApplicationBySchoolId($schoolId);
        return view('school_manager.application.list', $this->dataForView);
    }


    /**
     * 编辑
     * @param ApplicationRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(ApplicationRequest $request) {
        $dao = new ApplicationDao();
        if($request->isMethod('post')) {
            $id = $request->get('id');
            $user = $request->user()->id;
            $status = $request->get('status');
            $data = ['last_update_by'=> $user, 'status'=>$status];
            $re = $dao->updateStatusById($id, $data);
            if($re) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'编辑成功');
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'编辑失败');
            }

            return redirect()->route('school_manager.students.applications-manager');

        }

        $id = $request->get('id');
        $this->dataForView['application'] = $dao->getApplicationById($id);
        return view('school_manager.application.edit', $this->dataForView);
    }
}
