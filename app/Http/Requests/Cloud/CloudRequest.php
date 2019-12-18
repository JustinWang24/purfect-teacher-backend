<?php


namespace App\Http\Requests\Cloud;

use App\Http\Requests\MyStandardRequest;

class CloudRequest extends MyStandardRequest
{

    public function authorize()
    {
        return true;
    }

}
