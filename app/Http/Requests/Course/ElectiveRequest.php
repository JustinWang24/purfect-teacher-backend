<?php

namespace App\Http\Requests\Course;

use App\Http\Requests\MyStandardRequest;

class ElectiveRequest extends MyStandardRequest
{
    public function authorize()
    {
        return true;
    }
}
