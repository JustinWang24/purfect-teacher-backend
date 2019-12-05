<?php


namespace App\Dao\Account;


use App\Models\Account\AccountCoreMessage;

class AccountCoreMessageDao
{

    /**
     * 获取账户中心消息
     * @param $schoolId
     * @return AccountCoreMessage
     */
    public function getCoreMassageBySchoolId($schoolId)
    {
        return AccountCoreMessage::where('school_id', $schoolId)
            ->where('status', AccountCoreMessage::STATUS_RELEASE)
            ->select('content')
            ->orderBy('created_at', 'desc')
            ->first();
    }


}
