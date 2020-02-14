<?php


namespace App\Http\Requests\Evaluate;


use App\Http\Requests\MyStandardRequest;

class EvaluateTeacherRecordRequest extends MyStandardRequest
{

    public function getRecordData() {
        $data = $this->get('evaluate');
        $user = $this->user();
        $data['school_id'] = $user->getSchoolId();
        $data['student']['user_id'] = $user->id;
        $data['student']['grade_id'] = $user->gradeUser->grade_id;
        return $data;
    }


    public function getStudentData() {
        $data = $this->get('student');
        $data['user_id'] = $this->user()->id;
        return $data;
    }


    /**
     * 课程表ID
     * @return mixed
     */
    public function getItemId() {
        return $this->get('item_id');
    }


    /**
     * 学周
     * @return mixed
     */
    public function getWeek() {
        return $this->get('week');
    }
}
