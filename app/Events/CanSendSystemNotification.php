<?php
namespace App\Events;
use App\User;

interface CanSendSystemNotification
{
    /**
     * 必须可以拿到接收学校
     * @return int
     */
    public function getSchoolId(): int ;

    /**
     * 可以拿到接收者
     * @return int
     */
    public function getTo(): int ;

    /**
     * 必须可以拿到发送者
     * @return int
     */
    public function getSender(): int ;

    /**
     * 必须可以拿到消息类别
     * @return int
     */
    public function getType(): int ;

    /**
     * 必须可以拿到消息级别
     * @return int
     */
    public function getPriority(): int ;
    /**
     * 必须可以拿到发送标题
     * @return string
     */
    public function getTitle(): string ;

    /**
     * 必须可以拿到发送内容
     * @return string
     */
    public function getContent(): string ;

    /**
     * 必须可以拿到消息分类
     * @return int
     */
    public function getCategory(): int ;

    /**
     * 必须可以拿到前端所需key
     * @return string
     */
    public function getAppExtra(): string ;

    /**
     * 必须可以拿到下一步
     * @return string
     */
    public function getNextMove(): string ;

}
