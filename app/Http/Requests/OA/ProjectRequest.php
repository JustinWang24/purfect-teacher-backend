<?php


namespace App\Http\Requests\OA;


use App\Http\Requests\MyStandardRequest;

class ProjectRequest extends MyStandardRequest
{

    /**
     * 获取项目数据
     * @return mixed
     */
    public function getProject() {
        $data = $this->get('project');
        $data['user_id'] = $this->user()->id;
        $data['school_id'] = $this->user()->getSchoolId();
        return $data;
    }


    /**
     * 获取成员数据
     * @return mixed
     */
    public function getMembers() {
        return $this->get('member');
    }


    /**
     * 获取任务数据
     * @return mixed
     */
    public function getTask() {
        return $this->get('task');
    }

}
