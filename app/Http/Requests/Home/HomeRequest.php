<?php


namespace App\Http\Requests\Home;

use App\Http\Requests\MyStandardRequest;

class HomeRequest extends MyStandardRequest
{

    public function getUserInfo()
    {
        return $this->get('user');
    }

    public function getUserProfile()
    {
        return $this->get('profile');
    }


}
