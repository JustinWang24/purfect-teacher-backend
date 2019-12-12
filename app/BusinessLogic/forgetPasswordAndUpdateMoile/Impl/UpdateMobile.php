<?php


namespace App\BusinessLogic\forgetPasswordAndUpdateMoile\Impl;

use App\Http\Requests\Login\LoginRequest;
use App\Dao\Users\UserDao;
use App\Models\Users\UserVerification;
use App\Utils\JsonBuilder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Utils\ReturnData\MessageBag;

class UpdateMobile
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
     * 修改手机号
     * @return MessageBag
     */
    public function Logic()
    {

        $user = $this->verificationUser();

        if ($user) {
            return  new MessageBag(JsonBuilder::CODE_ERROR, '该手机号已注册');
        }

        if (!Hash::check($this->password, $this->user->password)) {
            return new MessageBag(JsonBuilder::CODE_ERROR, '原密码错误');
        }

        $code = $this->verificationCode(UserVerification::PURPOSE_3);
        if (empty($code)) {
             return  new MessageBag(JsonBuilder::CODE_ERROR, '验证码错误');
        }

        if (Carbon::now()->timestamp - strtotime($code->created_at) > 60) {
            return  new MessageBag(JsonBuilder::CODE_ERROR, '验证码已过期');
        }

        $userDao = new UserDao;
        $result = $userDao->updateUser($this->user->id, $this->mobile);
        if ($result) {
             $this->updateToken($this->user->id);
             return  new MessageBag(JsonBuilder::CODE_SUCCESS, '手机号修改成功,请重新登录');
        } else {
            return  new MessageBag(JsonBuilder::CODE_ERROR, '系统错误,请稍后再试~');
        }
    }


}
