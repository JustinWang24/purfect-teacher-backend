<?php

namespace App\Http\Requests\Teacher;

use App\Http\Requests\MyStandardRequest;
use App\Models\Teachers\ConferencesUser;

class ConferenceRequest extends MyStandardRequest
{

    public function getFormData() {
        $data = $this->get('conference');
        $data['user_id'] = $this->user()->id;
        $data['school_id'] = $this->getSchoolId();
        return $data;
    }

    /**
     * 签到类型
     * @return mixed
     */
    public function getType() {
        return $this->get('type', ConferencesUser::SIGN_IN);
    }

}
