<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 7/11/19
 * Time: 7:18 PM
 */

namespace App\Dao\Misc;

use App\Models\Misc\SystemNotification;
use App\Models\Misc\SystemNotificationsReadLog;
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
     *
     * @param $schoolId
     * @param $userId
     * @param int $pageSize
     * @return mixed
     */
    public function getNotificationByUserId($schoolId, $userId, $pageSize=ConfigurationTool::DEFAULT_PAGE_SIZE)
    {
       return  $this->_build($schoolId, $userId)->orderBy('priority','desc')
        ->orderBy('id','desc')
        ->simplePaginate($pageSize);
    }

    /**
     * 设置消息为已读
     * @param $schoolId
     * @param $userId
     * @return bool
     */
    public function setNotificationHasRead($schoolId, $userId){
        $maxNotificationId = $this->_build($schoolId, $userId)->max('id');
        return $maxNotificationId && SystemNotificationsReadLog::updateOrCreate(['user_id' => $userId], ['system_notifications_maxid' => $maxNotificationId]);
    }

    /**
     * 检查消息是否已读
     * @param $schoolId
     * @param $userId
     * @return bool
     */
    public function checkNotificationHasRead($schoolId, $userId) {
        $maxNotificationId = $this->_build($schoolId, $userId)->max('id');
        $readLogMaxId = SystemNotificationsReadLog::where('user_id', $userId)->value('system_notifications_maxid');
        return $readLogMaxId >= $maxNotificationId;
    }

    /**
     * 通用的查看自己消息的sql
     * @param $schoolId
     * @param $userId
     * @return mixed
     */
    private function _build($schoolId, $userId) {
        return SystemNotification::where(function ($query){
            // 1: 系统发出的消息, 此类消息 school_id 为 0, 表示任何学校的用户都可以接收
            $query->where('school_id',0)->where('to',0);
        })->orWhere(function ($query) use($schoolId){
            // 2: 学校发出的消息, to 的值为 0, 表示该学校的所有的用户都可以收到
            $query->where('school_id',$schoolId)->where('to',0);
        })->orWhere(function ($query) use($userId){
            // 3: to 的值为 user id, 表示发给自己的消息
            $query->where('to',$userId);
        });
    }
}
