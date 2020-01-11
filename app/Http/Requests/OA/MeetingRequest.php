<?php


namespace App\Http\Requests\OA;


use App\Http\Requests\MyStandardRequest;

class MeetingRequest extends MyStandardRequest
{
    public function getMeetId() {
        return $this->get('meetid');
    }

    public function getType() {
        return $this->get('type');
    }
}
