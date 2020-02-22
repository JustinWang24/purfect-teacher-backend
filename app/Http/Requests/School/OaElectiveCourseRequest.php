<?php
namespace App\Http\Requests\School;


use App\Http\Requests\MyStandardRequest;

class OaElectiveCourseRequest extends MyStandardRequest
{
    public function getInputType() {
        return $this->get('type', 0);
    }
    public function getInputPage() {
        return $this->get('page', 1);
    }
    public function getInputApplyid() {
        return $this->get('applyid');
    }
    public function getInputData() {
        /*
            'course' => [
                'name' => '课程名称',
                'open_num' => 10,//开班人数
                'majors' => [],//面向转向
                'desc' => '课程描述',
                'apply_content' => '申请理由',
                'campuse_id' => 1,//学院
            ],
            'schedule' => [
                'weeks' => [1,2],//周
                'days' => [2],星期几
                'timeSlots' => [1],//时间id
            ]
         */
        $course = $this->get('course');
        $schedule = $this->get('schedule');
        return ['course' => $course, 'schedule' => [$schedule]];
    }
}
