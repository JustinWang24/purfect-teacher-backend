<?php

namespace App\Http\Controllers\Api\Home;

use App\Dao\Banners\BannerDao;
use App\Dao\Users\UserDao;
use App\Events\User\ForgetPasswordEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BannerRequest;
use App\Http\Requests\Home\HomeRequest;
use App\Dao\Schools\NewsDao;
use App\Http\Requests\SendSms\SendSmeRequest;
use App\Models\Users\UserVerification;
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
        $data['school_name'] = $school->name;
        $data['school_logo'] = $school->logo;
        return JsonBuilder::Success($data);
    }


    /**
     * banner
     * @param BannerRequest $request
     * @return string
     */
    public function banner(BannerRequest $request)
    {
        $posit = $request->get('posit');

        $school = $request->getAppSchool();

        if (empty($school)) {
            return JsonBuilder::Error('未找到学校');
        }

        $dao = new BannerDao;

        $data = $dao->getBannerBySchoolIdAndPosit($school->id, $posit);

        return JsonBuilder::Success($data);
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
