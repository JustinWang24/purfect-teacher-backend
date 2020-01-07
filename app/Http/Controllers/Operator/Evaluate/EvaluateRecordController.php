<?php


namespace App\Http\Controllers\Operator\Evaluate;


use App\Dao\Evaluate\EvaluateTeacherRecordDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Evaluate\EvaluateTeacherRecordRequest;

class EvaluateRecordController extends Controller
{
    public function list(EvaluateTeacherRecordRequest $request){
        $id = $request->get('id');
        $dao = new EvaluateTeacherRecordDao();
        $list = $dao->getRecordByEvalTeacherId($id);
        $this->dataForView['list'] = $list;
        return view('school_manager.evaluate.record.list',$this->dataForView);
    }

}
