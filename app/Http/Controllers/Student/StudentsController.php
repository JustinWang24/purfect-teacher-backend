<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Dao\Users\UserDao;
use App\Http\Requests\User\StudentRequest;

class StudentsController extends Controller
{
    public function edit(StudentRequest $request){
        $dao = new UserDao();
        $this->dataForView['student'] = $dao->getUserByUuid($request->uuid());
        return view('student.edit', $this->dataForView);
    }
}
