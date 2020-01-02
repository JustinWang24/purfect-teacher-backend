<?php


namespace App\Http\Controllers\Api\Evaluate;


use App\Http\Controllers\Controller;
use App\Http\Requests\Evaluate\EvaluateTeacherRecordRequest;

class EvaluateTeacherRecordController extends Controller
{
    public function create(EvaluateTeacherRecordRequest $request) {
        $data = $request->getRecordData();
        dd($data);
    }
}
