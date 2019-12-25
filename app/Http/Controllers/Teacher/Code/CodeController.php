<?php


namespace App\Http\Controllers\Teacher\Code;


use App\Dao\Users\UserCodeRecordDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;

class CodeController extends Controller
{

    public function list(MyStandardRequest $request) {
        $schoolId = $request->getSchoolId();
        $dao = new UserCodeRecordDao();
        $list = $dao->getCodeRecordBySchoolId($schoolId);

        $this->dataForView['pageTitle'] = '二维码使用记录';
        $this->dataForView['list'] = $list;
        return view('teacher.code.list', $this->dataForView);
    }


}
