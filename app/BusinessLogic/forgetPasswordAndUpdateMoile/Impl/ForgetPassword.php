<?php


namespace App\BusinessLogic\forgetPasswordAndUpdateMoile\Impl;

use App\Dao\Users\UserDao;
use App\Http\Requests\Login\LoginRequest;
use App\Models\Users\UserVerification;
use App\Utils\JsonBuilder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Utils\ReturnData\MessageBag;

class ForgetPassword
{
    use VerificationTrait;

    private $user;

    private $mobile;

    private $code;

    private $password;

    public function __construct(LoginRequest $request)
    {
        $this->user   = $request->user();
        $this->mobile = $request->get('mobile');
        $this->code   = $request->get('code');
        $this->password = $request->getPassword();
    }

    /**
     * 忘记密码
     * @return MessageBag
     */
    public function Logic()
    {

        $user = $this->verificationUser();

        if (empty($user)) {
            return  new MessageBag(JsonBuilder::CODE_ERROR, '用户不存在');
        }

        $code = $this->verificationCode(UserVerification::PURPOSE_2);

        if (empty($code)) {
              return  new MessageBag(JsonBuilder::CODE_ERROR, '验证码错误');
        }

        if (Carbon::now()->timestamp - strtotime($code->created_at) > 60) {
            return  new MessageBag(JsonBuilder::CODE_ERROR, '验证码已过期');
        }

        $userDao = new UserDao;
        $result = $userDao->updateUser($user->id, null, Hash::make($this->password));
        if ($result) {
             $this->updateToken($user->id);
             return  new MessageBag(JsonBuilder::CODE_SUCCESS, '密码修改成功,请重新登录');
        } else {
            return  new MessageBag(JsonBuilder::CODE_ERROR, '系统错误,请稍后再试~');
        }
    }

}
