<?php

namespace App\Http\Requests\RecruitStudent;

use App\Http\Requests\MyStandardRequest;

class PlanRecruitRequest extends MyStandardRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * 从可能的地方获取 school 的 ID
     * @return mixed
     */
    public function getSchoolId()
    {
        return $this->has('school') ? $this->get('school') : $this->get('id');
    }

    /**
     * 获取所要查询的是哪一年的招生简章
     * @return int|mixed
     */
    public function getYear(){
        return $this->has('year') ? $this->get('year') : intval(date('Y'))+1;
    }
}
