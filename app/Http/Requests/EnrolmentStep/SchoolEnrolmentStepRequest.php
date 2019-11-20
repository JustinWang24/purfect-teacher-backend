<?php

namespace App\Http\Requests\EnrolmentStep;

use App\Http\Requests\MyStandardRequest;

class SchoolEnrolmentStepRequest extends MyStandardRequest
{
    /**
     * 获取表单提交
     * @return mixed
     */
    public function getFormData() {
        return $this->get('enrolment');
    }


    /**
     * 获取学校迎新步骤ID
     * @return mixed
     */
    public function getEnrolmentId() {
        return $this->get('id');
    }
}
