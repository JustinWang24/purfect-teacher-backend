<?php


namespace App\Http\Requests\OA;


use App\Http\Requests\MyStandardRequest;

class GroupRequest extends MyStandardRequest
{

    public function getGroupName() {
        return $this->get('name');
    }


    public function getKeyWord() {
        return $this->get('keyword');
    }

    public function getGroupId() {
        return $this->get('groupid');
    }

}
