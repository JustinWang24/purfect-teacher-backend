<?php


namespace App\Http\Controllers\Api\Evaluate;


use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
use App\Dao\Evaluate\EvaluateTeacherRecordDao;
use App\Http\Requests\Evaluate\EvaluateTeacherRecordRequest;

class EvaluateTeacherRecordController extends Controller
{
    public function create(EvaluateTeacherRecordRequest $request) {
        $data = $request->getRecordData();
        $dao = new EvaluateTeacherRecordDao();
        $result = $dao->create($data);
        $msg = $result->getMessage();
        if($result->isSuccess()) {
            return JsonBuilder::Success($msg);
        } else {
            return JsonBuilder::Error($msg);
        }
    }
}
