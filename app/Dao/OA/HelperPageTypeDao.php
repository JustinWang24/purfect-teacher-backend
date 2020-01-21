<?php


namespace App\Dao\OA;


use App\Models\OA\HelperPageType;
use App\User;

class HelperPageTypeDao
{
    /**
     * 获取公共的
     * @param User $user
     * @return HelperPageType
     */
    public function getCommunalHelperPageByUser(User $user)
    {
        return HelperPageType::where('school_id', $user->getSchoolId())
            ->where('status', HelperPageType::STATUS_NORMAL)
            ->where('type', HelperPageType::TYPE_COMMUNAL)
            ->orderBy('sort', 'desc')
            ->get();
    }

    /**
     * 获取自己的
     * @param User $user
     * @return HelperPageType
     */
    public function getHelperPageByUser(User $user)
    {
        return HelperPageType::where('school_id', $user->getSchoolId())
            ->where('status', HelperPageType::STATUS_NORMAL)
            ->where('type', $user->getType())
            ->orderBy('sort', 'desc')
            ->get();
    }
}
