<?php

namespace App\Http\Requests\Teacher;

use App\Http\Requests\MyStandardRequest;

class ConferenceRequest extends MyStandardRequest
{

    public function getFormData() {
        $data = $this->get('conference');
        $data['user_id'] = $this->user()->id;
        $data['school_id'] = $this->getSchoolId();
        return $data;
    }

}
