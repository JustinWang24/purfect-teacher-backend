<?php

namespace App\Http\Controllers\Student;

use App\Dao\Users\UserDao;
use App\Utils\FlashMessageBuilder;
use App\Http\Controllers\Controller;
use App\Dao\Students\StudentProfileDao;
use App\Http\Requests\User\StudentRequest;

class StudentsController extends Controller
{
    public function edit(StudentRequest $request){
        $dao = new UserDao();
        $this->dataForView['student'] = $dao->getUserByUuid($request->uuid());
        return view('student.edit', $this->dataForView);
    }


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
}
