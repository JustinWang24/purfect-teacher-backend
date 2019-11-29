<?php

namespace App\Http\Requests;

class SchoolRequest extends MyStandardRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function getConfiguration(){
        return $this->get('config');
    }

    /**
     * 获取选修课有效期的表单
     * @param $term
     * @return array
     */
    public function getElectiveCourseAvailableTerm($term){
        return $this->get('ec'.$term);
    }
}
