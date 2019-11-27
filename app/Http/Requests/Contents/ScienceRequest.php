<?php


namespace App\Http\Requests\Contents;


use App\Http\Requests\MyStandardRequest;

class ScienceRequest extends MyStandardRequest
{

    public function getFormData() {
        $data = $this->get('science');
        $data['school_id'] = $this->getSchoolId();
        return $data;
    }
}
