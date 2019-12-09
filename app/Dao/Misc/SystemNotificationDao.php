<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 7/11/19
 * Time: 7:18 PM
 */

namespace App\Dao\Misc;

use App\Models\Misc\SystemNotification;
use App\Utils\Misc\ConfigurationTool;

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


    /**
     * 根据学校ID 获取通知
     * @param $where
     * @return mixed
     */
    public function getNotificationByUserId($schoolId, $userId)
    {
        return SystemNotification::where('school_id', $schoolId)->
                    whereIn('to', [0,$userId])->simplePaginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }



}
