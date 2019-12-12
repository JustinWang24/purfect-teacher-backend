<?php

namespace App\Http\Controllers\Api\Home;

use App\Dao\Banners\BannerDao;
use App\Dao\Calendar\CalendarDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\Students\StudentProfileDao;
use App\Dao\Users\UserDao;
use App\Events\User\ForgetPasswordEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BannerRequest;
use App\Http\Requests\Home\HomeRequest;
use App\Dao\Schools\NewsDao;
use App\Http\Requests\MyStandardRequest;
use App\Http\Requests\SendSms\SendSmeRequest;
use App\Models\Students\StudentProfile;
use App\Models\Users\UserVerification;
use App\User;
use App\Utils\JsonBuilder;

class IndexController extends Controller
{

    /**
     * APP首页
     * @param HomeRequest $request
     * @return string
     */
    public function index(HomeRequest $request)
    {
        $school = $request->getAppSchool();
        $pageNum = $request->get('pageNum');
        $dao = new NewsDao;

        $data = $dao->getNewBySchoolId($school->id, $pageNum);

        foreach ($data as $key => $val ) {
            $data[$key]['time'] = $val['created_at'];
            $data[$key]['image'] = "";
            foreach ($val->sections as $new) {
                if (!empty($new->meia)) {
                    $data[$key]['image'] = $new->media->url;
                }
            }
            unset($data[$key]['sections']);
            unset($data[$key]['created_at']);
        }

        $data = pageReturn($data);
        $data['is_show_account'] = false; // 是否展示账户
        $data['school_name'] = $school->name;
        $data['school_logo'] = $school->logo;
        return JsonBuilder::Success($data);
    }


    /**
     * 获取资源位 Banner 的接口
     * @param BannerRequest $request
     * @return string
     */
    public function banner(BannerRequest $request)
    {
        $posit = $request->get('posit');
        $publicOnly = $request->has('public') && intval($request->get('public',0)) === 1;
        $dao = new BannerDao;
        $data = $dao->getBannerBySchoolIdAndPosit($request->user()->getSchoolId(), $posit, $publicOnly);
        return JsonBuilder::Success($data);
    }

    /**
     * 获取校历的接口
     * 可以提交学校的 id 或者名称进行查询. 如果未提供, 则以当前登陆用户的信息为准, 获取该用户所在学校的校历信息
     * 根据当前登陆用户, 当前的调用时间, 获取当前学期校历的接口
     *
     * @param MyStandardRequest $request
     * @return string
     */
    public function calendar(MyStandardRequest $request){
        $schoolIdOrName = $request->get('school', null);
        $dao = new SchoolDao();
        if($schoolIdOrName){
            $school = $dao->getSchoolById($schoolIdOrName);
            if(!$school){
                $school = $dao->getSchoolByName($schoolIdOrName);
            }
        }
        else{
            $school = $dao->getSchoolById($request->user()->getSchoolId());
        }

        if(!$school){
            return JsonBuilder::Error('找不到学校的信息');
        }
        else{
            $dao = new CalendarDao();
            return JsonBuilder::Success([
                'calendar'=>$dao->getCalendar($school->configuration)
            ]);
        }
    }

    /**
     * 获取用户信息
     * @param HomeRequest $request
     * @return string
     */
    public function getUserInfo(HomeRequest $request)
    {
        $user = $request->user();

        $profile = $user->profile;

        $gradeUser = $user->gradeUser;
        $grade     = $user->gradeUser->grade;

        $data = [
            'name'        => $user->name,
            'gender'      => $profile->gender,
            'birthday'    => $profile->birthday,
            'state'       => $profile->state,
            'city'        => $profile->city,
            'area'        => $profile->area,
            'school_name' => $gradeUser->school->name,
            'institute'   => $gradeUser->institute->name,
            'department'  => $gradeUser->department->name,
            'major'       => $gradeUser->major->name,
            'year'        => $grade->year,
            'grade_name'  => $grade->name
        ];

        return  JsonBuilder::Success($data);
    }

    /**
     * 修改用户信息
     * @param HomeRequest $request
     * @return string
     */
    public function updateUserInfo(HomeRequest $request)
    {
        $user = $request->user();

        $dao = new StudentProfileDao;

        $result = $dao->updateStudentProfile($user->id, $request->get('data'));

        if ($result) {
            return  JsonBuilder::Success('修改成功');
        } else {
            return  JsonBuilder::Success('修改失败');
        }
    }



    /**
     * 发送短信
     * @param SendSmeRequest $request
     * @return string
     */
    public function sendSms(SendSmeRequest $request)
    {
        $mobile = $request->get('mobile');
        $type   = $request->get('type');

        $dao = new  UserDao;

        $user = $dao->getUserByMobile($mobile);

        if ($type == UserVerification::PURPOSE_0 && !empty($user)) {
            return JsonBuilder::Error('该手机号已经注册过了');
        }

        if ($type == UserVerification::PURPOSE_2 && empty($user)) {
            return JsonBuilder::Error('该手机号还未注册');
        }

        switch ($type) {
            case UserVerification::PURPOSE_0:
                // TODO :: 注册发送验证码
                break;
            case UserVerification::PURPOSE_2:
                event(new ForgetPasswordEvent($user));
                break;
        default:
            break;
        }

        return JsonBuilder::Success('发送成功');
    }
}
