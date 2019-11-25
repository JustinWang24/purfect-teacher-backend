<?php

namespace App\Http\Requests\School;

use App\Http\Requests\MyStandardRequest;

class ApplicationRequest extends MyStandardRequest
{

    public function getApplicationTypeFormData() {
        return $this->get('type');
    }
}
