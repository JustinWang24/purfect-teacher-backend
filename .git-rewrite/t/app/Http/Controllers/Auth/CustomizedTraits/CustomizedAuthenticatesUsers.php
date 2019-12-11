<?php
/**
 * 改变 Laravel 提供的 AuthenticatesUsers 的一些行为, 用于在 Login 的过程中
 */

namespace App\Http\Controllers\Auth\CustomizedTraits;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Dao\Schools\SchoolDao;
use Illuminate\Support\Facades\Auth;
use App\Dao\Users\GradeUserDao;

trait CustomizedAuthenticatesUsers
{
    use AuthenticatesUsers;

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            // 登陆成功, 将学校的信息放到 Session 中
            /**
             * @var User $user
             */
            $user = Auth::user();
            if(!$user->isSchoolAdminOrAbove()){
                // 该用户最多是个教师或者学生
                $gradeUserDao = new GradeUserDao($user);
                $schoolsId = $gradeUserDao->getSchoolsId();

                if(count($schoolsId) === 1){
                    // 获取学校
                    $dao = new SchoolDao($user);
                    $school = $dao->getSchoolById($schoolsId[0]->school_id);
                    $school->savedInSession($request);
                }else{
                    // Todo: 非管理员用户同时在多个学校的处理方式: 让用户选择进入哪个学校
                }
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
