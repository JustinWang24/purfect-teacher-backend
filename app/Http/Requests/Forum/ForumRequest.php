<?php


namespace App\Http\Requests\Forum;

use App\Http\Requests\MyStandardRequest;

class ForumRequest extends MyStandardRequest
{

    public function getFormData(){
        return $this->get('forum');
    }
}
