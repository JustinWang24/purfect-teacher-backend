<?php

namespace App\Http\Controllers\Api\Home;

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
use Illuminate\Http\Request;

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

        foreach ($data as $key => $val) {
            $data[$key]['created_at']  = $val['updated_at'];
            $data[$key]['webview_url'] = route('h5.teacher.news.view', ['id' => $val['id']]);
            $data[$key]['image']       = "";
            foreach ($val->sections as $new) {
                if (!empty($new->media)) {
                    $data[$key]['image'] = asset($new->media->url);
                    break;
                }
            }
            unset($data[$key]['sections']);
            unset($data[$key]['updated_at']);
        }

        $data                    = pageReturn($data);
        $data['is_show_account'] = false; // 是否展示账户
        $data['school_name']     = $school->name;
        $data['school_logo']     = $school->logo;

        //首页消息获取
        $user                  = $request->user();
        $systemNotificationDao = new SystemNotificationDao();
        $systemNotifications   = $systemNotificationDao->getNotificationByUserId($school->id, $user->id, 2);
        $json_array            = [];
        foreach ($systemNotifications as $key => $value) {
            $json_array[$key]['ticeid']       = $value->id;
            $json_array[$key]['create_at']    = $value->create_id;
            $json_array[$key]['tice_title']   = $value->title;
            $json_array[$key]['tice_content'] = $value->content;
            $json_array[$key]['tice_money']   = $value->money;
            $json_array[$key]['webview_url']  = $value->next_move;
            $json_array[$key]['type']         = $value->type;
            $json_array[$key]['priority']     = $value->priority;
            if (isset(SystemNotification::CATEGORY[$value->category])) {
                $json_array[$key]['tice_header'] = SystemNotification::CATEGORY[$value->category];
            } else {
                $json_array[$key]['tice_header'] = '消息';
            }
        }
        $data['notifications'] = $json_array;


        return JsonBuilder::Success($data);
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
     * 下发所有 校历 events 的接口
     * @param MyStandardRequest $request
     * @return string
     */
    public function all_events(MyStandardRequest $request)
    {
        $school = $this->_getSchoolFromRequest($request);

        if (!$school) {
            return JsonBuilder::Error('找不到学校的信息');
        } else {
            $dao    = new CalendarDao();
            $events = $dao->getCalendarEvent($school->id, date('Y'));
            $weeks  = $school->configuration->getAllWeeksOfTerm();

            $data = [];

            foreach ($events as $event) {
                foreach ($weeks as $week) {
                    /**
                     * @var CalendarWeek $week
                     */
                    if ($week->includes($event->event_time)) {
                        $event->week_idx = $week->getName();
                        $event->name     = $event->event_time;
                        $data[]          = $event;
                        break;
                    }
                }
            }

            return JsonBuilder::Success([
                'events' => $data
            ]);
        }
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
            'name'           => $user->name,
            'nice_name'      => $user->nice_name,
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

        $schoolId   = $user->getSchoolId();
        $dao        = new SchoolDao;
        $schoolName = $dao->getSchoolById($schoolId);

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
            'name'           => $user->name,
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
        if ($data['nice_name']) {
            $userDao = new UserDao;
            $result = $userDao->updateUser($user->id, null,null,null,null, $data['nice_name']);
        } else {
            $dao        = new StudentProfileDao;
            $teacherDao = new TeacherProfileDao;
            if ($user->isStudent()) {
                $result = $dao->updateStudentProfile($user->id, $data);
            } elseif ($user->isTeacher()) {
                $result = $teacherDao->updateTeacherProfile($user->id, $data);
            }
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
                event(new UpdateUserMobileEvent($user, $mobile));
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
        foreach ($list as $key => $val) {
            $list[$key]['webview_url'] = route('h5.teacher.news.view', ['id' => $val['id']]);
            $list[$key]['image']       = '';
            $sections                  = $val->sections;
            foreach ($sections as $k => $v) {
                if (!empty($v->media)) {
                    $list[$key]['image'] = asset($v->media->url);
                    break;
                }
            }
            unset($list[$key]['sections']);
            unset($list[$key]['updated_at']);
        }
        $data = pageReturn($list);
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

}
