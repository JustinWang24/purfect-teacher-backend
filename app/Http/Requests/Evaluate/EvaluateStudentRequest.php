<?php


namespace App\Http\Requests\Evaluate;


use App\Http\Requests\MyStandardRequest;

class EvaluateStudentRequest extends MyStandardRequest
{
    public function getStudentData() {
        $data = $this->get('student');
        $data['user_id'] = $this->user()->id;
        return $data;
    }
}
