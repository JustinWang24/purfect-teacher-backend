<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 11/11/19
 * Time: 4:07 PM
 */

namespace App\Events;
use App\User;

interface CanReachByMobilePhone
{
    /**
     * 必须可以拿到电话号码
     * @return string
     */
    public function getMobileNumber(): string ;

    /**
     * 必须可以拿到短信通知的模板 ID
     * @return string
     */
    public function getSmsTemplateId(): string ;

    /**
     * 必须可以拿到需要发送的短信的动态内容数组
     * @return array
     */
    public function getSmsContent(): array ;

    /**
     * 必须可以拿到用户的对象
     * @return User
     */
    public function getUser(): User;
}
