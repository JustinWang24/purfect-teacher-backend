<?php


namespace App\Http\Requests\OA;


use App\Http\Requests\MyStandardRequest;

class MeetingRequest extends MyStandardRequest
{
    public function getMeetId() {
        return $this->get('meet_id');
    }

    public function getType() {
        return $this->get('type');
    }
}
