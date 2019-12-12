<?php


namespace App\BusinessLogic\forgetPasswordAndUpdateMoile;

use App\Http\Requests\Login\LoginRequest;
use App\Models\Users\UserVerification;
use App\BusinessLogic\forgetPasswordAndUpdateMoile\Impl\ForgetPassword;
use App\BusinessLogic\forgetPasswordAndUpdateMoile\Impl\UpdateMobile;

class Factory
{
    public static function GetLogic(LoginRequest $request)
    {
        $instance = null;

        $type = $request->get('type');

        if ($type == UserVerification::PURPOSE_0) {
            // todo :: 短信注册
        }
        elseif($type == UserVerification::PURPOSE_1) {
           // todo :: 短信登录
        }
        elseif($type == UserVerification::PURPOSE_2) {
            $instance = new ForgetPassword($request);
        }
        elseif ($type == UserVerification::PURPOSE_3) {
            $instance = new UpdateMobile($request);
        }

        return $instance;
    }


}
