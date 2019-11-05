<?php

namespace App\Http\Requests\RecruitStudent;

use App\Http\Requests\MyStandardRequest;

class PlanRecruitRequest extends MyStandardRequest
{
     public function authorize()
    {
        return true;
    }

}
