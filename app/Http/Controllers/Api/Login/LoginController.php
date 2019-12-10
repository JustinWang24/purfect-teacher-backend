<?php

namespace App\Http\Controllers\Api\Login;

use App\Dao\Users\UserDao;
use App\Dao\Users\UserDeviceDao;
use App\Dao\Users\UserVerificationDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Login\LoginRequest;
use App\Models\Users\UserVerification;
use Illuminate\Support\Facades\Auth;
use App\Utils\JsonBuilder;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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

            $dao           = new UserDao;
            $userDeviceDao = new UserDeviceDao;

            $user = $dao->getUserByMobile($credentials['mobile']);

            if ($user->getType() != $request->getAppType()) {
                return JsonBuilder::Error('登录APP版本与您的账号不符,请登录对应的APP');
            } else {

                $userDeviceDao->updateOrCreate($user->getId(), $request->getUserDevice());

                $token  = Uuid::uuid4()->toString();
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

    /**
     * 用户退出
     * @param LoginRequest $request
     * @return string
     * @throws \Exception
     */
    public function logout(LoginRequest $request)
    {
        $user = $request->user();
        if (!$user) {
            return JsonBuilder::Error('未找到用户');
        }

        $dao    = new UserDao;
        $token  = Uuid::uuid4()->toString();
        $result = $dao->updateApiToken($user->id, $token);
        if ($result) {
            return JsonBuilder::Success('退出成功');
        } else {
            return JsonBuilder::Error('系统错误,请稍后再试~');
        }
    }


    /**
     * 修改密码
     * @param LoginRequest $request
     * @return string
     * @throws \Exception
     */
    public function editPassword(LoginRequest $request)
    {
        $user        = $request->user();
        $password    = $request->getPassword();
        $newPassword = $request->get('new_password');

        if (!Hash::check($password, $user->password)) {
            return JsonBuilder::Error('原密码错误');
        }

        $dao = new UserDao;

        $result = $dao->updateUser($user->id, null, $newPassword);
        if ($result) {
            $dao->updateApiToken($user->id, null);
            return JsonBuilder::Success('密码修改成功,请重新登录');
        } else {
            return JsonBuilder::Error('系统错误,请稍后再试~');
        }

    }

    /**
     * 忘记密码
     * @param LoginRequest $request
     * @return string
     * @throws \Exception
     */
    public function forgetPassword(LoginRequest $request)
    {

        $mobile   = $request->getMobile();
        $password = $request->getPassword();
        $code     = $request->get('code');

        $dao = new UserVerificationDao;
        $userDao = new UserDao;

        $user = $userDao->getUserByMobile($mobile);

        if (empty($user)) {
            return JsonBuilder::Error('用户不存在');
        }

        $verification = $dao->getVerificationByMobileAdnCode($mobile, $code, UserVerification::PURPOSE_2);

        if (empty($verification)) {
            return JsonBuilder::Error('验证码错误');
        }

        if (Carbon::now()->timestamp - strtotime($verification->created_at) > 60) {
            return JsonBuilder::Error('验证码已过期');
        }

        $result = $userDao->updateUser($user->id, null, $password);
        if ($result) {
            $userDao->updateApiToken($user->id, null);
            return JsonBuilder::Success('密码修改成功,请重新登录');
        } else {
            return JsonBuilder::Error('系统错误,请稍后再试~');
        }


    }


}
