<?php

namespace App\Http\Controllers\Api\Login;

use App\Dao\Users\UserDao;
use App\Dao\Users\UserDeviceDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Login\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Utils\JsonBuilder;
use Ramsey\Uuid\Uuid;

class LoginController extends Controller
{

    /**
     * 登录
     * @param LoginRequest $request
     * @return string
     * @throws \Exception
     */
    public function index(LoginRequest $request)
    {
        $credentials = $request->only('mobile', 'password');

        if (Auth::attempt($credentials)) {

            $dao = new UserDao;
            $userDeviceDao = new UserDeviceDao;

            $user = $dao->getUserByMobile($credentials['mobile']);

            if ($user->getType() != $request->getAppType()){
                return JsonBuilder::Error('登录APP版本与您的账号不符,请登录对应的APP');
            } else {

                $userDeviceDao->updateOrCreate($user->getId(), $request->getUserDevice());

                $token = Uuid::uuid4()->toString();
                $result = $dao->updateApiToken($user->getId(), $token);
                if ($result) {
                    return JsonBuilder::Success(['token' => $token]);
                } else {
                    return JsonBuilder::Error('系统错误,请稍后再试~');
                }

            }
        } else {
            return JsonBuilder::Error('账号或密码错误,请重新登录');
        }
    }

}
