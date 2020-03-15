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

    /**
     * {
     *   id:1,
     *   school_id:1,
     *   title:"名称",
     *   wifi_name:"指定wifi",
     *   using_afternoon:true,
     * }
     */
    public function getAttendanceData() {
        return $this->get('attendance');
    }

    /**
     * [
     * [1,2,3,4]
     * ]
     * @return mixed
     */
    public function getOrganizationsData() {
        return $this->get('organizations');
    }

    /**
     * [1,2,3]//前端不接受这样格式
     * [
     *   {
     *      "id":1,
     *      "name":""
     *   }
     * ]
     * @return mixed
     */
    public function getMenagersData(){
        return $this->get('managers');
    }

    /**
     * [
     *   {
     *      week:"Monday",
     *      start:"08:00:00",
     *      end:"22:00:00",
     *      morning:"09:00:00",
     *      morning_late:"09:30:00",
     *      afternoon_start:"12:00:00",
     *      afternoon:"13:00:00",
     *      afternoon_late:"13:30:00",
     *      evening:"18:00:00",
     *      is_weekday:true
     *   }
     * ]
     * @return mixed
     */
    public function getClockSetsData() {
        return $this->get('clocksets');
    }
}
