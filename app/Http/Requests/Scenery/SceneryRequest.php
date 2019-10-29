<?php

namespace App\Http\Requests\Scenery;

use App\Http\Requests\MyStandardRequest;

class SceneryRequest extends MyStandardRequest
{

    /**
     * session 应存一下 userId
     * @return mixed
     */
    public function getUserId()
    {
         return $this->session()->get('user.id',null);
    }







}
