<?php

namespace App\Events\SystemNotification;

use App\Events\CanSendSystemNotification;
use App\Models\Misc\SystemNotification;
use App\Models\OA\Project;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class OaProjectEvent implements CanSendSystemNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private  $user;
    private  $projectId;

    /**
     * OaMessageEvent constructor.
     * @param $userid
     */
    public function __construct($userId, $projectId)
    {
        $this->user = User::find($userId);
        $this->projectId = $projectId;
    }

    /**
     * 必须可以拿到接收学校
     * @return int
     */
    public function getSchoolId(): int
    {
        return $this->user->getSchoolId();
    }

    /**
     * 可以拿到接收者
     * @return int
     */
    public function getTo(): int
    {
        return $this->user->id;
    }

    /**
     * 必须可以拿到发送者
     * @return int
     */
    public function getSender(): int
    {
        return SystemNotification::FROM_SYSTEM;//系统
    }

    /**
     * 必须可以拿到消息类别
     * @return int
     */
    public function getType(): int
    {
        return SystemNotification::TYPE_NONE;
    }

    /**
     * 必须可以拿到消息级别
     * @return int
     */
    public function getPriority(): int
    {
        return SystemNotification::PRIORITY_LOW;
    }
    /**
     * 必须可以拿到发送标题
     * @return string
     */
    public function getTitle(): string
    {
        return '你有一条新的项目信息！';
    }

    /**
     * 必须可以拿到发送内容
     * @return string
     */
    public function getContent(): string
    {
        $project = Project::find($this->projectId);
        return $project->title;
    }

    /**
     * 必须可以拿到消息分类
     * @return int
     */
    public function getCategory(): int
    {
        return SystemNotification::TEACHER_CATEGORY_PROJECT;
    }

    /**
     * 必须可以拿到前端所需key
     * @return string
     */
    public function getAppExtra(): string
    {
        $extra = [
            'type' => 'oa-project-info',
            'param1' => $this->projectId,
            'param2' => ''
        ];
        return json_encode($extra);
    }

    /**
     * 必须可以拿到下一步
     * @return string
     */
    public function getNextMove(): string
    {
        return '';
    }

    /**
     * 必须可以拿到组织id
     * @return array
     */
    public function getOrganizationIdArray(): array
    {
        return [];//单人消息 无组织可见范围
    }




}
