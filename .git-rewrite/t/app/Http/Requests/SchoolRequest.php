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
}
