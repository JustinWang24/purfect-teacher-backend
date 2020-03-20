<?php

namespace App\Http\Controllers\Api\Home;

use App\Dao\Affiche\CommonDao;
use App\Dao\Banners\BannerDao;
use App\Dao\Calendar\CalendarDao;
use App\Dao\Misc\SystemNotificationDao;
use App\Dao\Notice\AppProposalDao;
use App\Dao\Notice\NoticeDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\Students\StudentProfileDao;
use App\Dao\Teachers\TeacherProfileDao;
use App\Dao\Users\UserDao;
use App\Dao\Wifi\Backstage\UsersDao;
use App\Events\User\ForgetPasswordEvent;
use App\Events\User\UpdateUserMobileEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BannerRequest;
use App\Http\Requests\Home\HomeRequest;
use App\Dao\Schools\NewsDao;
use App\Http\Requests\MyStandardRequest;
use App\Http\Requests\SendSms\SendSmeRequest;
use App\Models\Forum\Forum;
use App\Models\Misc\SystemNotification;
use App\Models\Notices\AppProposal;
use App\Models\Notices\AppProposalImage;
use App\Models\Students\StudentProfile;
use App\Models\Teachers\Teacher;
use App\Models\Users\UserVerification;
use App\Utils\JsonBuilder;
use App\Utils\Misc\SmsFactory;
use App\Utils\Time\CalendarWeek;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{

    /**
     * APP首页
     * @param HomeRequest $request
     * @return string
     */
    public function index(HomeRequest $request)
    {
        $school  = $request->getAppSchool();
        $pageNum = $request->get('pageNum', 5);
        $dao     = new NewsDao;

        $data = $dao->getNewBySchoolId($school->id, $pageNum);
        $result = [];
        $list = [];
        foreach ($data as $key => $val) {
            $list[$key]['id'] = $val['id'];
            $list[$key]['title'] = $val['title'];
            $list[$key]['type'] = $val['type'];
            $list[$key]['tags'] = $val['tags'];
            $list[$key]['created_at']  = $val['created_at']->format('Y-m-d');
            $list[$key]['webview_url'] = route('h5.teacher.news.view', ['id' => $val['id']]);
            $list[$key]['image']       = "";
            foreach ($val->sections as $new) {
                if (!empty($new->media)) {
                    $list[$key]['image'] = asset($new->media->url);
                    break;
                }
            }
        }
        $result['list'] = $list;
        $result['is_show_account'] = false; // 是否展示账户
        $result['school_name']     = $school->name;
        $result['school_logo']     = $school->logo;

        //首页消息获取
        $user                  = $request->user();
        $systemNotificationDao = new SystemNotificationDao();
        $systemNotifications   = $systemNotificationDao->getNotificationByUser($school->id, $user, 2)->toArray();
        $commonDao = new CommonDao();
        foreach ($systemNotifications['data'] as &$systemNotification) {
            $systemNotification['created_at'] = $commonDao->transTime3(strtotime($systemNotification['created_at']));
        }
        //获取消息是否已读
        $systemNotificationHasRead = $systemNotificationDao->checkNotificationHasRead($school->id, $user);
        $result['notifications_list'] = $systemNotifications;
        $result['notifications_read'] = $systemNotificationHasRead;

        return JsonBuilder::Success($result);
    }


    /**
     * 获取资源位 Banner 的接口
     * @param BannerRequest $request
     * @return string
     */
    public function banner(BannerRequest $request)
    {
        $posit      = $request->get('posit');
        $publicOnly = $request->has('public') && intval($request->get('public', 0)) === 1;
        $dao        = new BannerDao;
        $data       = $dao->getBannerBySchoolIdAndPosit($request->user()->getSchoolId(), $posit, $publicOnly);
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
    public function calendar(MyStandardRequest $request)
    {
        $school = $this->_getSchoolFromRequest($request);
        if (!$school) {
            return JsonBuilder::Error('找不到学校的信息');
        } else {
            $dao = new CalendarDao();
            return JsonBuilder::Success(
                $dao->getCalendar($school->configuration, $request->get('year'), $request->get('month'))
            );
        }
    }

    /**
     * 历史记录
     * @param MyStandardRequest $request
     * @return string
     */
    public function all_events(MyStandardRequest $request) {
        $school = $this->_getSchoolFromRequest($request);
        if (!$school) {
            return JsonBuilder::Error('找不到学校的信息');
        }
        $data = $this->events($school,'history');
        return JsonBuilder::Success(['events' => $data]);
    }


    public function events($school, $type = 'all') {
        $dao    = new CalendarDao();
        if($type == 'all') {
            $events = $dao->getCalendarEvent($school->id, date('Y'));
        } elseif($type == 'history') {
            $events = $dao->getCalendarEvent($school->id, null, true);
        }

        foreach ($events as $key => $event) {
            $event->week_idx = '第'. $event->week_idx .'周';
            $event->name = $event->event_time;
        }
        return $events;
    }

    /**
     * @param MyStandardRequest $request
     * @return \App\Models\School
     */
    private function _getSchoolFromRequest(MyStandardRequest $request)
    {
        $schoolIdOrName = $request->get('school', null);
        $dao            = new SchoolDao();
        if ($schoolIdOrName) {
            $school = $dao->getSchoolById($schoolIdOrName);
            if (!$school) {
                $school = $dao->getSchoolByName($schoolIdOrName);
            }
        } else {
            $school = $dao->getSchoolById($request->user()->getSchoolId());
        }

        return $school;
    }

    /**
     * 获取学生用户信息
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
            'mobile'         => $user->mobile,
            'student_id'     => $user->id,
            'name'           => $user->name,
            'nice_name'      => $user->nice_name,
            'user_signture'  => $user->user_signture,
            'avatar'         => $profile->avatar,
            'gender'         => $profile->gender,
            'birthday'       => $profile->birthday,
            'state'          => $profile->state,
            'city'           => $profile->city,
            'area'           => $profile->area,
            'student_number' => $profile->student_number,
            'id_number'      => $profile->id_number,
            'school_name'    => $gradeUser->school->name,
            'institute'      => $gradeUser->institute->name,
            'department'     => $gradeUser->department->name,
            'major'          => $gradeUser->major->name,
            'year'           => $grade->year,
            'grade_name'     => $grade->name
        ];
        return JsonBuilder::Success($data);
    }


    /**
     * 获取教师用户信息
     * @param HomeRequest $request
     * @return string
     */
    public function getTeacherInfo(HomeRequest $request)
    {
        $user = $request->user();

        $profile = $user->profile;

//        $schoolId   = $user->getSchoolId();
//        $dao        = new SchoolDao;
//        $schoolName = $dao->getSchoolById($schoolId);

        $allDuties = Teacher::getTeacherAllDuties($user->id);

        if ($allDuties['gradeManger']) {
            $gradeManger = $allDuties['gradeManger']->grade->name;
        } else {
            $gradeManger = '';
        }

        if ($allDuties['organization']) {
            $organization = $allDuties['organization']->title;
        } else {
            $organization = '';
        }

        if ($allDuties['myYearManger']) {
            $yearManger = $allDuties['myYearManger']->year . '年级组长';
        } else {
            $yearManger = '';
        }

        if ($allDuties['myTeachingAndResearchGroup']) {
            foreach ($allDuties['myTeachingAndResearchGroup'] as $key => $group) {
                $myGroup[$key]['name']     = $group->name;
                $myGroup[$key]['isLeader'] = $group->isLeader;
            }
        } else {
            $myGroup = [];
        }

        $data = [
            'user_id'        => $user->id,
            'name'           => $user->name,
            'mobile'         => $user->mobile,
            'avatar'         => $profile->avatar,
            'id_number'      => $profile->id_number,
            'serial_number'  => $profile->serial_number,
            'group_name'     => $profile->group_name,
            'gender'         => $profile->gender,
            'birthday'       => $profile->birthday,
            'education'      => $profile->education,
            'degree'         => $profile->degree,
            'political_name' => $profile->political_name,
            'title'          => $profile->title,
            'work_start_at'  => $profile->work_start_at,
            'organization'   => $organization,
            'gradeManger'    => $gradeManger,
            'yearManger'     => $yearManger,
            'myGroup'        => $myGroup,
            'institute'      => '',
            'department'     => '',
            'major'          => '',

        ];
        return JsonBuilder::Success($data);
    }

    /**
     * 修改用户信息
     * @param HomeRequest $request
     * @return string
     */
    public function updateUserInfo(HomeRequest $request)
    {
        $user   = $request->user();
        $data   = $request->get('data');
        $avatar = $request->file('avatar');
        if ($avatar) {
            $avatarImg      = $avatar->store('public/avatar');
            $data['avatar'] = StudentProfile::avatarUploadPathToUrl($avatarImg);
        }
        $userDao = new UserDao;
        if (isset($data['nice_name'])) {
            $result = $userDao->updateUser($user->id, null,null,null,null, $data['nice_name'],null);
        } else {
            $dao        = new StudentProfileDao;
            $teacherDao = new TeacherProfileDao;
            if ($user->isStudent()) {
                $result = $dao->updateStudentProfile($user->id, $data);
            } elseif ($user->isTeacher()) {
                $result = $teacherDao->updateTeacherProfile($user->id, $data);
            }
        }
        if (isset($data['user_signture'])) {
            $result = $userDao->updateUser($user->id, null,null,null,null, null, $data['user_signture']);
        }

        if ($result) {
            return JsonBuilder::Success('修改成功');
        } else {
            return JsonBuilder::Success('修改失败');
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

        if ($type == UserVerification::PURPOSE_3) {
            $token = $request->get('api_token');
            $forgetPwdUser  = $dao->getUserByApiToken($token);
        }

        if ($type == UserVerification::PURPOSE_0 && !empty($user)) {
            return JsonBuilder::Error('该手机号已经注册过了');
        }

        if ($type == UserVerification::PURPOSE_2 && empty($user)) {
            return JsonBuilder::Error('该手机号还未注册');
        }
        if ($type == UserVerification::PURPOSE_3 && !empty($user)) {
            return JsonBuilder::Error('该手机号已经注册过了');
        }

        switch ($type) {
            case UserVerification::PURPOSE_0:
                // TODO :: 注册发送验证码, 目前没注册功能
                break;
            case UserVerification::PURPOSE_2:
                event(new ForgetPasswordEvent($user));
                break;
            case UserVerification::PURPOSE_3:
                /** @var TYPE_NAME $forgetPwdUser */
                event(new UpdateUserMobileEvent($forgetPwdUser, $mobile));
            default:
                break;
        }
        return JsonBuilder::Success('发送成功');
    }


    /**
     * 校园动态分页
     * @param HomeRequest $request
     * @return string
     */
    public function newsPage(HomeRequest $request)
    {
        $schoolId = $request->user()->getSchoolId();
        $dao      = new NewsDao();
        $list     = $dao->getNewBySchoolId($schoolId);
        $result  = [];
        foreach ($list as $key => $val) {
            $result[$key]['id'] = $val['id'];
            $result[$key]['type'] = $val['type'];
            $result[$key]['title'] = $val['title'];
            $result[$key]['tags'] = $val['tags'];
            $result[$key]['created_at'] = $val->created_at->format('Y-m-d H:i');
            $result[$key]['webview_url'] = route('h5.teacher.news.view', ['id' => $val['id']]);
            $result[$key]['image']       = '';
            $sections                  = $val->sections;
            foreach ($sections as $k => $v) {
                if (!empty($v->media)) {
                    $result[$key]['image'] = asset($v->media->url);
                    break;
                }
            }
        }

        $data['list'] = $result;
        return JsonBuilder::Success($data);
    }

    /**
     * 为 APP 端 加载各种新闻的接口
     * @param Request $request
     * @return string
     */
    public function loadNews(Request $request)
    {
        $dao      = new NewsDao();
        $newsList = $dao->paginateByType(
            $request->get('type'),
            $request->get('school')
        );
        return JsonBuilder::Success($newsList);
    }

    /**
     * 为 APP 获取通知公告
     * @param Request $request
     * @return string
     */
    public function loadNotices(Request $request)
    {
        $dao      = new NoticeDao();
        $newsList = $dao->getNoticeBySchoolId(['school_id' => $request->get('school')]);
        return JsonBuilder::Success($newsList);
    }

    /**
     * 意见反馈
     * @param Request $request
     * @return string
     */
    public function proposal(Request $request)
    {
        $user    = $request->user();
        $type    = $request->get('type');
        $content = $request->get('content');
        $images  = $request->file('image');

        $path = [];
        if (!empty($images)) {
            foreach ($images as $image) {
                $path[] = AppProposalImage::proposalUploadPathToUrl($image->store(AppProposalImage::DEFAULT_UPLOAD_PATH_PREFIX));
            }
        }

        $dao  = new AppProposalDao;
        $data = [
            'user_id' => $user->id,
            'type'    => $type,
            'content' => $content,
        ];

        $result = $dao->add($data, $path);

        if ($result) {
            return JsonBuilder::Success('反馈成功');
        } else {
            return JsonBuilder::Error('反馈失败');
        }
    }

    /**
     * 反馈列表
     * @param Request $request
     * @return string
     */
    public function proposalList(Request $request)
    {
        $user = $request->user();
        $dao  = new AppProposalDao;

        $data   = $dao->getProposalByUserId($user->id);
        $result = pageReturn($data);

        return JsonBuilder::Success($result);
    }

    /**
     * 服务协议及隐私政策
     */
    public function agreement()
    {
        $arr = [" ","　","\t","\n","\r"];

        $data = [
            'top' => '请你务必审慎同意,需要充分理解“服务协议”及隐私政策条款,为了给你提供内容分享、考勤打卡等服务,我们需要收集
            你的设备信息、操作日志等信息.你可以在设置中查看、变更你的账号信息',

            'content'=> '本软件尊重并保护所有使用服务用户的个人隐私权。为了给您提供更准确、更有个性化的服务，本软件会按照本隐私
            权政策的规定使用和披露您的个人信息。但本软件将以高度的勤勉、审慎义务对待这些信息。除本隐私权政策另有规定外，
            在未征得您事先许可的情况下，本软件不会将这些信息对外披露或向第三方提供。本软件会不时更新本隐私权政策.您在同意本软件
            服务使用协议之时,即视为您已经同意本隐私权政策全部内容。本隐私权政策属于本软件服务使用协议不可分割的一部分。
            1.适用范围
            a)在您使用本软件网络服务，本软件自动接收并记录的您的手机上的信息，包括但不限于您的健康数据、使用的语言、访问日期和时间、
            软硬件特征信息及您需求的网页记录等数据；
            2.信息的使用
            a)在获得您的数据之后，本软件会将其上传至服务器，以生成您的排行榜数据，以便您能够更好地使用服务。
            3.信息披露
            a)本软件不会将您的信息披露给不受信任的第三方。
            b)根据法律的有关规定，或者行政或司法机构的要求，向第三方或者行政、司法机构披露；
            c)如您出现违反中国有关法律、法规或者相关规则的情况，需要向第三方披露；
            4.信息存储和交换
            本软件收集的有关您的信息和资料将保存在本软件及（或）其关联公司的服务器上，这些信息和资料可能传送至您所在国家、地区或
            本软件收集信息和资料所在地的境外并在境外被访问存储和展示
            5.信息安全
            a)在使用本软件网络服务进行网上交易时，您不可避免的要向交易对方或潜在的交易对方披露自己的个人信息，
            如联络方式或者邮政地址。请您妥善保护自己的个人信息，仅在必要的情形下向他人提供。如您发现自己的个人信息泄密，
            请您立即联络本软件客服，以便本软件采取相应措施。'
        ];
        $result = [];
        foreach ($data as $key => $val) {
            $result[][$key] = str_replace($arr, '', $val);
        }
        return JsonBuilder::Success($result);
    }

  /**
   * 校园新闻详情
   * @param MyStandardRequest $request
   * @return string
   */
    public function newsInfo(MyStandardRequest $request)
    {
        $news = (new NewsDao())->getById($request->get('id'));

        return JsonBuilder::Success($news);
    }
}
