<?php


namespace App\Http\Controllers\Operator\Evaluate;


use App\Dao\Evaluate\EvaluateTeacherRecordDao;
use App\Http\Requests\Evaluate\EvaluateTeacherRecordRequest;

class EvaluateRecordController
{
    public function list(EvaluateTeacherRecordRequest $request)
    {
        $id = $request->get('id');
        $dao = new EvaluateTeacherRecordDao();
        $list = $dao->getRecordByEvalTeacherId($id);
        dd($list);
    }

}
