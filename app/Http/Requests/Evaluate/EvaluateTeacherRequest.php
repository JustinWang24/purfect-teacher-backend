<?php


namespace App\Http\Requests\Evaluate;


use App\Http\Requests\MyStandardRequest;

class EvaluateTeacherRequest extends MyStandardRequest
{


    public function getFormDate() {
        return $this->get('evaluate-teacher');
    }
}
