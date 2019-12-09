<?php

namespace App\Dao\Users;

use App\Models\Users\UserDevice;

class UserDeviceDao
{

    /**
     * 根据用户ID 查询设备号
     * @param $userId
     * @return mixed
     */
    public function getUserDeviceByUserId($userId)
    {
        return UserDevice::where('user_id', $userId)->first();
    }


    public function updateOrCreate($userId, $data = [])
    {
        $device = $this->getUserDeviceByUserId($userId);

        if (empty($device)) {
            $data['user_id'] = $userId;
            return $user = UserDevice::create($data);
        } else {
            return $user = UserDevice::where('user_id', $userId)->update($data);
        }
    }

}
