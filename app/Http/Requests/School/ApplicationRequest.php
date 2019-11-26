<?php

namespace App\Http\Requests\School;

use App\Http\Requests\MyStandardRequest;

class ApplicationRequest extends MyStandardRequest
{

    /**
     * 获取提交申请类型表单
     * @return mixed
     */
    public function getApplicationTypeFormData() {
        return $this->get('type');
    }


    /**
     * 获取提交申请表单
     * @return mixed
     */
    public function getApplicationFormData() {
        $data = $this->get('application');
        $data['user_id'] = $this->user()->id;
        $data['school_id'] = $this->user()->getSchoolId();
        $data['grade_id'] = $this->user()->gradeUser->grade_id;
        return $data;
    }


    /**
     * 获取编辑申请
     * @return mixed
     */
    public function getApplicationEditFormData() {
        $data = $this->get('application');
        if(empty($data['status'])) {
            unset($data['status']);
        }
        $data['last_update_by'] = $this->user()->id;
        return $data;
    }
}
