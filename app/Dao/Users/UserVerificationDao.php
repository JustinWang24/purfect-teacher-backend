<?php

namespace App\Dao\Users;

use App\Models\Users\UserVerification;

class UserVerificationDao
{

    /**
     * 根据手机号验证码 查询
     * @param $mobile
     * @param $code
     * @param $purpose
     * @return UserVerification
     */
    public function getVerificationByMobileAdnCode($mobile, $code, $purpose)
    {
        return UserVerification::where(['mobile' => $mobile, 'code' => $code, 'purpose' => $purpose])->first();
    }


}
