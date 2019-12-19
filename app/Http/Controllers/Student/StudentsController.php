<?php
namespace App\Http\Controllers\Student;

use App\Dao\Users\UserDao;
use App\Models\Schools\GradeManager;
use App\Utils\FlashMessageBuilder;
use App\Http\Controllers\Controller;
use App\Dao\Students\StudentProfileDao;
use App\Http\Requests\User\StudentRequest;

class StudentsController extends Controller
{
    /**
     * @param StudentRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(StudentRequest $request){
        $dao = new UserDao();
        $student = $dao->getUserByUuid($request->uuid());
        $this->dataForView['gradeManager'] = GradeManager::where('monitor_id',$student->id)->first();
        $this->dataForView['student'] = $student;
        $this->dataForView['pageTitle'] = '档案管理';
        return view('student.edit', $this->dataForView);
    }

    /**
     * @param StudentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StudentRequest $request) {
        $data = $request->getFormData();
        $userId = $data['user']['id'];
        $uuid = $data['user']['uuid'];
        unset($data['user']['uuid']);
        unset($data['user']['id']);
        $dao = new StudentProfileDao();
        $result = $dao->updateStudentInfoByUserId($userId, $data['user'], $data['profile']);

        if($result->isSuccess()){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'编辑成功');
        }else{
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'编辑失败');
        }
        return redirect()->route('verified_student.profile.edit',['uuid'=>$uuid]);
    }

    /**
     * 通讯录页面
     * @param StudentRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contacts_list(StudentRequest $request){
        $this->dataForView['pageTitle'] = '通讯录';
        $this->dataForView['schoolId'] = $request->getSchoolId();

        return view('student.contacts.list',$this->dataForView);
    }
}
