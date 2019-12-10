<?php


namespace App\Dao\Account;


use App\Models\Account\AccountCore;

class AccountCoreDao
{
    /**
     * 获取充值中心金额
     * @param $schoolId
     * @return AccountCore
     */
    public function getAccountCoreMoneyBySchoolId($schoolId)
    {
        return AccountCore::where('school_id', $schoolId)
            ->where('status', AccountCore::STATUS_RELEASE)
            ->select('id', 'fictitious_money', 'actual_money', 'vip_money')
            ->orderBy('fictitious_money', 'desc')
            ->get();
    }

}
