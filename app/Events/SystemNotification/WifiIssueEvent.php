<?php

namespace App\Events\SystemNotification;

use App\Events\CanSendSystemNotification;
use App\Models\Misc\SystemNotification;
use App\Models\Wifi\Backstage\WifiIssues;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class WifiIssueEvent implements CanSendSystemNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private  $wifiIssue;
    const WIFIISSUE_STATUS_ACCEPT = 2;
    const WIFIISSUE_STATUS_FINISH = 3;
    const WIFIISSUE_STATUS_ACCEPT_TEXT = '受理';
    const WIFIISSUE_STATUS_FINISH_TEXT = '完成';

    /**
     * WifiIssueEvent constructor.
     * @param WifiIssues $wifiIssue
     */
    public function __construct(WifiIssues $wifiIssue)
    {
        $this->wifiIssue = $wifiIssue;
    }

    /**
     * 必须可以拿到接收学校
     * @return int
     */
    public function getSchoolId(): int
    {
        return $this->wifiIssue->school_id;
    }

    /**
     * 可以拿到接收者
     * @return int
     */
    public function getTo(): int
    {
        return $this->wifiIssue->user_id;
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
        return '你的报修已' . $this->getStatusText($this->wifiIssue->status) . '！';
    }

    /**
     * 必须可以拿到发送内容
     * @return string
     */
    public function getContent(): string
    {
        return $this->getStatusText($this->wifiIssue->status) . '时间：' . Carbon::now()->format('Y-m-d H:i');
    }

    /**
     * 必须可以拿到消息分类
     * @return int
     */
    public function getCategory(): int
    {
        return SystemNotification::COMMON_CATEGORY_WIFI;
    }

    /**
     * 必须可以拿到前端所需key
     * @return string
     */
    public function getAppExtra(): string
    {
        $extra = [
            'type' => 'wifi-issue-info',
            'param1' => $this->wifiIssue->id,
            'param2' => ''
        ];
        return json_encode($extra);
        return '';
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

    private function getStatusText($status)
    {
        return self::getStatusAll()[$status];
    }
    public static function getStatusAll()
    {
        return [
            self::WIFIISSUE_STATUS_ACCEPT => self::WIFIISSUE_STATUS_ACCEPT_TEXT,
            self::WIFIISSUE_STATUS_FINISH => self::WIFIISSUE_STATUS_FINISH_TEXT
        ];
    }




}
