<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 7/11/19
 * Time: 7:18 PM
 */

namespace App\Dao\Misc;

use App\Models\Misc\SystemNotification;

class SystemNotificationDao
{

    public function __construct()
    {
    }

    /**
     * 创建系统消息
     * @param $data
     * @return SystemNotification
     */
    public function create($data){
        return SystemNotification::create($data);
    }
}