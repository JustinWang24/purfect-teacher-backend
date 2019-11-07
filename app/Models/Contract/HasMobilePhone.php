<?php
/**
 * 有手机号码的类接口
 */

namespace App\Models\Contract;

interface HasMobilePhone
{
    /**
     * 获取手机号码的接口
     * @return string
     */
    public function getMobile();

    /**
     * 获取持有手机的人的名字
     * @return string
     */
    public function getName();
}