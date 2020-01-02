<?php


namespace App\Http\Requests\Evaluate;


use App\Http\Requests\MyStandardRequest;

class EvaluateTeacherRecordRequest extends MyStandardRequest
{

    public function getRecordData() {
        $data = $this->get('evaluate');
        $user = $this->user();
        $data['user_id'] = $user->id;
        $data['grade_id'] = $this->user()->getSchoolId();
        return $data;
    }
}
