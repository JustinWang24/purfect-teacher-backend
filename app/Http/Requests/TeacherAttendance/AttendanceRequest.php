<?php

namespace App\Http\Requests\TeacherAttendance;

use App\Http\Requests\MyStandardRequest;
use Carbon\Carbon;

class AttendanceRequest extends MyStandardRequest
{
    public function getInputGroupId() {
        return $this->get('groupid', 0);
    }
    public function getInputDay() {
        return $this->get('day', Carbon::now()->format('Y-m-d'));
    }
    public function getInputMacAddress() {
        return $this->get('mac_address');
    }
    public function getInputWifi() {
        return $this->get('wifi');
    }
    public function getInputMonth() {
        return $this->get('month', Carbon::now()->format('Y-m'));
    }
}
