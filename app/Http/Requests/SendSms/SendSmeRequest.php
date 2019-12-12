<?php


namespace App\Http\Requests\SendSms;

use App\Http\Requests\MyStandardRequest;

class SendSmeRequest extends MyStandardRequest
{
    public function authorize()
    {
        return true;
    }
}
