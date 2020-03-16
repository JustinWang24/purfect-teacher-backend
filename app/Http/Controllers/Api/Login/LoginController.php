<?php

namespace App\Http\Controllers\Api\Login;

use App\BusinessLogic\forgetPasswordAndUpdateMoile\Factory;
use App\Dao\Students\StudentProfileDao;
use App\Dao\Teachers\TeacherProfileDao;
use App\Dao\Users\UserDao;
use App\Dao\Users\UserDeviceDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Login\LoginRequest;
use App\Models\Acl\Role;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Utils\JsonBuilder;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;

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
        $type = $request->get('type');
        $dao  = new UserDao;

        if ($type == User::MOBILE_LOGIN) {

            $credentials = $request->only('mobile', 'password');
            if (!Auth::attempt($credentials)) {
                return JsonBuilder::Error('账号或密码错误,请重新登录');
            } else {
                $user = $dao->getUserByMobile($credentials['mobile']);
            }

        } elseif ($type == User::ID_NUMBER_LOGIN) {

            $idNumber = $request->get('id_number');
            $password = $request->get('password');

            if (!$idNumber || !$password) {
                return JsonBuilder::Error('请输入身份证号或者密码');
            }

            if ($request->getAppType() == Role::VERIFIED_USER_STUDENT) { // 学生登录
                 $studentProfile = new StudentProfileDao;
                 $profile = $studentProfile->getStudentInfoByIdNumber($idNumber);
            } else {
                 $teacherProfile = new TeacherProfileDao;
                 $profile = $teacherProfile->getTeacherProfileByIdNumber($idNumber);
            }
            if (!$profile) {
                return JsonBuilder::Error('身份证号输入错误,请重新登录');
            }

            $user = $profile->user;
            if (!Hash::check($password, $user->password)) {
                 return JsonBuilder::Error('身份证号或密码错误,请重新输入');
            }
        }
        $userDeviceDao = new UserDeviceDao;
        if ($user->getType() != $request->getAppType()) {
            if ($user->getType() == Role::SCHOOL_MANAGER) {

            } else {
              return JsonBuilder::Error('登录APP版本与您的账号不符,请登录对应的APP');
            }
        } else {
            $userDeviceDao->updateOrCreate($user->getId(), $request->getUserDevice());
            $token  = Uuid::uuid4()->toString();
            $result = $dao->updateApiToken($user->getId(), $token);
            if ($user->status == User::STATUS_WAITING_FOR_MOBILE_TO_BE_VERIFIED || $user->status == User::STATUS_WAITING_FOR_IDENTITY_TO_BE_VERIFIED) {
                $userStatus = false;
            } else {
                $userStatus = true;
            }
            if ($result) {
                return JsonBuilder::Success(['token' => $token, 'status' => $userStatus, 'user_id' => $user->getId()]);
            } else {
                return JsonBuilder::Error('系统错误,请稍后再试~');
            }
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
        $logic  = Factory::GetLogic($request);
        $result = $logic->Logic();
        if ($result->isSuccess()) {
            return JsonBuilder::Success($result->getMessage());
        } else {
            return JsonBuilder::Error($result->getMessage());
        }
    }


    /**
     * 修改手机号
     * @param LoginRequest $request
     * @return string
     */
    public function updateUserMobileInfo(LoginRequest $request)
    {
        $logic  = Factory::GetLogic($request);
        $result = $logic->Logic();
        if ($result->isSuccess()) {
            return JsonBuilder::Success($result->getMessage());
        } else {
            return JsonBuilder::Error($result->getMessage());
        }
    }


}
