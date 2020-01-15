<?php


namespace App\Http\Requests\OA;


use App\Http\Requests\MyStandardRequest;
use App\Models\OA\ProjectTask;

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
        $data = $this->get('task');
        if(empty($data['user_id'])) {
            $data['user_id'] = $this->user()->id;
        }
        return $data;
    }


    /**
     * 获取讨论数据
     * @return mixed
     */
    public function getDiscussion() {
        $data = $this->get('discussion');
        $data['user_id'] = $this->user()->id;
        return $data;
    }


    /**
     * 获取项目ID
     * @return mixed
     */
    public function getProjectId() {
        return $this->get('project_id', null);
    }


    /**
     * 获取任务ID
     * @return mixed
     */
    public function getTaskId() {
        return $this->get('taskid', null);
    }


    /**
     * 获取任务类型
     * @return mixed
     */
    public function getTaskType() {
        return $this->get('type',ProjectTask::STATUS_UN_BEGIN);
    }


    /**
     * 获取讨论ID
     * @return mixed
     */
    public function getForumId() {
        return $this->get('forumid');
    }


    /**
     * 获取关键字
     * @return mixed
     */
    public function getKeyword() {
        return $this->get('keyword');
    }
}
