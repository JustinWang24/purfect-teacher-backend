<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('school')->middleware('auth:api')->group(function () {
    // 加载学校的作息时间表
    Route::any('/load-time-slots','Api\School\TimeSlotsController@load_by_school')
        ->name('api.school.load.time.slots');

    Route::any('/save-time-slot','Api\School\TimeSlotsController@save_time_slot')
        ->name('api.school.save.time.slot');

    // 获取指定学校的所有老师的键值对数据
    Route::post('/teachers','Api\School\UsersController@teachers')
        ->name('api.school.get.teachers');

    // 为课程表添加功能提供返回有效时间段的接口
    Route::any('/load-study-time-slots','Api\School\TimeSlotsController@load_study_time_slots')
        ->name('api.school.load.study.time.slots');

    // 获取某个学校所有的专业
    Route::any('/load-majors','Api\School\MajorsController@load_by_school')
        ->name('api.school.load.majors');

    // 获取某个专业的所有班级
    Route::any('/load-major-grades','Api\School\MajorsController@load_major_grades')
        ->name('api.school.load.major.grades');

    // 获取某个专业的所有课程
    Route::any('/load-major-courses','Api\School\MajorsController@load_major_courses')
        ->name('api.school.load.major.courses');

    // 获取某个学校所有的课程
    Route::any('/load-courses','Api\School\CoursesController@load_courses')
        ->name('api.school.load.courses');

    // 搜索某个学校的老师
    Route::any('/search-teachers','Api\School\UsersController@search_by_name')
        ->name('api.school.search.teachers');

    Route::any('/get-user-name','Api\School\UsersController@get_user_name')
        ->name('api.school.get.user.name.by.id');

    // 根据给定的课程, 返回所有教授该课程的老师的列表
    Route::any('/load-course-teachers','Api\School\UsersController@load_course_teachers')
        ->name('api.school.load.course.teachers');

    // 保存课程的接口
    Route::any('/save-course','Api\School\CoursesController@save_course')
        ->name('api.school.save.course');

    // 删除课程的接口
    Route::any('/delete-course','Api\School\CoursesController@delete_course')
        ->name('api.school.delete.course');

    // 获取学校的所有建筑
    Route::any('/load-buildings','Api\School\LocationController@load_buildings')
        ->name('api.school.load.buildings');

    // 根据给定的建筑, 加载建筑内所有房间的接口
    Route::any('/load-building-rooms','Api\School\LocationController@load_building_rooms')
        ->name('api.school.load.building.rooms');

    // 根据条件为课程表创建表单返回未被占用的房间
    Route::any('/load-building-available-rooms','Api\School\LocationController@load_building_available_rooms')
        ->name('api.school.load.building.available.rooms');

    // 快速定位用户的搜索
    Route::any('/quick-search-users','Api\School\UsersController@quick_search_users')
        ->name('api.school.quick.search.users');

    // APP 应用接口
    Route::any('/calendar','Api\Home\IndexController@calendar')
        ->name('api.school.calendar');  // 校历接口

    Route::any('/all-events','Api\Home\IndexController@all_events')
        ->name('api.school.all-events'); // 历史事件
});

Route::prefix('enquiry')->middleware('auth:api')->group(function () {
    // 保存课程表项的接口
    Route::post('/save','Api\Enquiry\EnquiriesController@save')
        ->name('api.enquiry.save');
});

Route::prefix('recruitment')->group(function () {
    // 加载某个学校的招生计划
    Route::any('/load-plans','Api\Recruitment\PlansController@load_plans')
        ->name('api.recruitment.load.plans');

    // 保存某个学校的招生计划
    Route::post('/save-plan','Api\Recruitment\PlansController@save_plan')
        ->name('api.recruitment.save.plan');

    // 加载某个招生计划: 后端加载时调用
    Route::any('/get-plan','Api\Recruitment\PlansController@get_plan')
        ->name('api.recruitment.get.plan');

    // 加载某个招生计划
    Route::post('/delete-plan','Api\Recruitment\PlansController@delete_plan')
        ->name('api.recruitment.delete.plan');

    // 加载某个学生的已报名专业
    Route::any('/my-enrolments','Api\Recruitment\PlansController@my_enrolments')
        ->name('api.recruitment.load.my-enrolments');
});

Route::prefix('timetable')->middleware('auth:api')->group(function () {
    // 保存课程表项的接口
    Route::post('/timetable-item-can-be-inserted','Api\Timetable\TimetableItemsController@can_be_inserted')
        ->name('api.timetable.item.can.be.inserted');

    // 保存课程表项的接口
    Route::post('/save-timetable-item','Api\Timetable\TimetableItemsController@save')
        ->name('api.timetable.save.item');

    // 克隆项目
    Route::post('/clone-timetable-item','Api\Timetable\TimetableItemsController@clone_item')
        ->name('api.timetable.clone.item');

    // 删除课程表项的接口
    Route::post('/delete-timetable-item','Api\Timetable\TimetableItemsController@delete')
        ->name('api.timetable.delete.item');

    // 发布课程表项的接口
    Route::post('/publish-timetable-item','Api\Timetable\TimetableItemsController@publish')
        ->name('api.timetable.publish.item');

    // 保存课程表项的接口
    Route::post('/update-timetable-item','Api\Timetable\TimetableItemsController@update')
        ->name('api.timetable.update.item');

    // 尝试加载课程表: 查询条件是只要有班级, 年和学期即可
    Route::post('/load','Api\Timetable\TimetableItemsController@load')
        ->name('api.timetable.load.items');

    // 尝试加载课程表项: 查询条件是id
    Route::post('/load-item','Api\Timetable\TimetableItemsController@load_item')
        ->name('api.timetable.load.item');

    // 创建课程表的调课项
    Route::post('/create-special-case','Api\Timetable\TimetableItemsController@create_special_case')
        ->name('api.timetable.create.special.case');

    // 尝试加载课程表的特定调课项: 查询条件是 ids, 即调课项的 id 集合
    Route::post('/load-special-cases','Api\Timetable\TimetableItemsController@load_special_cases')
        ->name('api.timetable.load.special.cases');

    // 给 APP 和前端页面使用的 API
    // 1: 根据学生的 api token, 获取今天的课表
    Route::any('/load-by-student','Api\Timetable\FrontendController@load_by_student')
        ->name('api.timetable.load-by-student');

    // 给 APP 教师端使用的 API: 课表
    // 1: 根据教师的 api token, 获取今天的课表
    Route::any('/load-by-teacher','Api\Timetable\FrontendController@load_by_teacher')
        ->name('api.timetable.load-by-teacher');
});

// 招生API
Route::prefix('student-register')->middleware('auth:api')->group(function () {
     // 获取全部招生计划
     Route::post('/load-open-majors','Api\Recruitment\PlansController@load_plans')
        ->name('api.load.open.majors');

     // 招生咨询接口
     Route::any('/qa','Api\Recruitment\PlansController@qa')
        ->name('api.load.plans.qa');

     // 专业详情: 前端加载是调用
     Route::post('/load-major-detail','Api\Recruitment\PlansController@get_plan_front')
        ->name('api.load.major.detail');

     // 报名辅助填充数据
     Route::post('/query-student-profile','Api\Recruitment\OpenMajorController@studentProfile')
        ->name('api.query.student.profile');

     // 报名
     Route::post('/submit-form','Api\Recruitment\OpenMajorController@signUp')
        ->name('api.major.submit.form');

     //
    Route::post('/submit-excel','Api\Recruitment\OpenMajorController@testExcel')
        ->name('api.major.submit.excel');

    // 验证学生身份证信息的接口
    Route::post('/verify-id-number','Api\Recruitment\OpenMajorController@verify_id_number')
        ->name('api.major.verify.id.number');

    // 批准和拒绝学生的报名
    Route::post('/approve-or-reject','Api\Recruitment\OpenMajorController@approve_or_reject')
        ->name('api.major.approve.or.reject');

    // 录取学生 或 拒绝录取学生
    Route::post('/enrol-or-reject','Api\Recruitment\OpenMajorController@enrol_or_reject')
        ->name('api.major.enrol.or.reject');
});

// 录取API
Route::prefix('employ')->middleware('auth:api')->group(function () {
     // 获取全部未分配班级的人
     Route::post('/get-unassigned-grades','Api\Recruitment\EmployController@index')
        ->name('api.get.unassigned.grades');

     // 分配班级
     Route::post('/distribution-grades','Api\Recruitment\EmployController@distribution')
        ->name('api.distribution.grades');
    // 录取和拒绝学生的报名
    Route::post('/enrol-or-reject','Api\Recruitment\OpenMajorController@enrol_or_reject')
        ->name('api.major.enrol.or.reject');
});


// APP 通讯录
Route::prefix('campus')->middleware('auth:api')->group(function () {
     // 班级通讯录
     Route::post('/handleAffairs/getAddressBook/class','Api\Address\AddressBookController@index')
        ->name('api.address.book.class');
     // 学校部门通讯录
     Route::post('/handleAffairs/getAddressBook/official','Api\Address\AddressBookController@official')
        ->name('api.address.book.official');
     // 教师通讯录
     Route::post('/handleAffairs/getAddressBook/teacher','Api\Address\AddressBookController@teacherMobile')
        ->name('api.address.book.teacher.mobile');
     // 加载所有的班级
     Route::post('/all-grades','Api\Address\AddressBookController@all_grades')
        ->name('api.school.all-grades');
});


Route::prefix('course')->middleware('auth:api')->group(function () {
     // 选修课列表
     Route::post('/elective/list','Api\Course\ElectiveController@index')
        ->name('api.course.elective.list');
    Route::post('/elective/mylist','Api\Course\ElectiveController@mylist')
        ->name('api.course.elective.mylist');
     // 选课详情
     Route::post('/elective/details','Api\Course\ElectiveController@details')
        ->name('api.course.elective.details');
     // 选课报名操作
     Route::post('/elective/enroll/{id}','Api\Course\ElectiveController@enroll')
        ->name('api.course.elective.enroll');
     // 选课查询报名结果操作
     Route::post('/elective/getresult/{id}','Api\Course\ElectiveController@getEnrollResult')
        ->name('api.course.elective.getresult');
     // 教师端课件
     Route::post('/getApiCourseDownloadListInfo','Api\Course\CourseWareController@index')
        ->name('api.teacher.course.ware');
});

// 网盘
Route::prefix('network-disk')->middleware('auth:api')->group(function () {
    // 创建目录
    Route::post('/categories/create','Api\NetworkDisk\CategoriesController@create')
        ->name('api.categories.create');
    // 编辑目录
    Route::post('/categories/edit','Api\NetworkDisk\CategoriesController@edit')
        ->name('api.categories.edit');
    // 目录下列表
    Route::post('/categories/view','Api\NetworkDisk\CategoriesController@view')
        ->name('api.categories.view');
    // 父级目录下列表
    Route::post('/categories/view-parent','Api\NetworkDisk\CategoriesController@view_parent')
        ->name('api.categories.view.parent');
    // 删除目录
    Route::post('/categories/delete','Api\NetworkDisk\CategoriesController@delete')
        ->name('api.categories.delete');

    Route::post('/media/upload','Api\NetworkDisk\MediaController@upload')
        ->name('api.media.file.upload');

    // 文件详情
    Route::post('/media/getMediaInfo','Api\NetworkDisk\MediaController@getMediaInfo')
        ->name('api.media.getMediaInfo');

    // 删除文件
    Route::post('/media/delete','Api\NetworkDisk\MediaController@delete')
        ->name('api.media.delete');
    // 移动文件
    Route::post('/media/move','Api\NetworkDisk\MediaController@move')
        ->name('api.media.move');
    // 搜索文件
    Route::post('/media/search','Api\NetworkDisk\MediaController@search')
        ->name('api.media.search');
    // 更新点击次数
    Route::post('/media/click','Api\NetworkDisk\MediaController@click')
        ->name('api.media.click');
    // 更新文件的星标
    Route::post('/media/update-asterisk','Api\NetworkDisk\MediaController@update_asterisk')
        ->name('api.media.update.asterisk');
    // 最近浏览和创建
    Route::post('/media/latelyUploadingAndBrowse','Api\NetworkDisk\MediaController@latelyUploadingAndBrowse')
        ->name('api.media.latelyUploadingAndBrowse');
    // 判断是否可以上传
    Route::post('/media/judgeIsUpload','Api\NetworkDisk\MediaController@judgeIsUpload')
        ->name('api.media.judgeIsUpload');

    // 查看用户的云盘空间
    Route::post('/media/getNetWorkDiskSize','Api\NetworkDisk\MediaController@getNetWorkDiskSize')
        ->name('api.media.getNetWorkDiskSize');

});

// 选修课申请
Route::prefix('elective-course')->middleware('auth:api')->group(function () {
    Route::post('/save','Api\ElectiveCourse\ApplyElectiveCourseController@create')
        ->name('api.elective-course.save');

    Route::post('/load','Api\ElectiveCourse\ApplyElectiveCourseController@load')
        ->name('api.elective-course.load');
});


Route::prefix('enrolment-step')->middleware('auth:api')->group(function () {

    // 获取系统迎新列表
    Route::any('/enrolmentStep/getEnrolmentStepList','Api\EnrolmentStep\EnrolmentStepController@getEnrolmentStepList')
        ->name('api.enrolmentStep.getEnrolmentStepList');

    // 创建/修改 学校迎新步骤
    Route::any('/schoolEnrolmentStep/saveEnrolment','Api\EnrolmentStep\SchoolEnrolmentStepController@saveEnrolment')
        ->name('api.schoolEnrolmentStep.saveEnrolment');

    // 学校迎新列表
    Route::any('/step-list','Api\EnrolmentStep\SchoolEnrolmentStepController@school_enrolment_step')
        ->name('api.school-enrolment-step.step-list');

    // 更新排序
    Route::any('/schoolEnrolmentStep/updateSort','Api\EnrolmentStep\SchoolEnrolmentStepController@updateSort')
        ->name('api.school-enrolment-step.update-sort');

    // 删除学校迎新步骤
    Route::any('/schoolEnrolmentStep/deleteEnrolment','Api\EnrolmentStep\SchoolEnrolmentStepController@deleteEnrolment')
        ->name('api.schoolEnrolmentStep.deleteEnrolment');
});

Route::prefix('enrolment-step')->group(function () {

    // 获取学校迎新步骤详情
    Route::any('/schoolEnrolmentStep/getEnrolmentInfo', 'Api\EnrolmentStep\SchoolEnrolmentStepController@getEnrolmentInfo')
        ->name('api.schoolEnrolmentStep.getEnrolmentInfo');
});

Route::prefix('students')->middleware('auth:api')->group(function () {
    // 创建申请
    Route::post('applications-create','Api\Application\ApplicationController@create')
            ->name('api.students.applications-create');
    // 申请类型
    Route::get('applications-type','Api\Application\ApplicationController@applicationTypeList')
            ->name('api.students.applications-type');

    // 根据用户查询申请列表
    Route::get('applications-list','Api\Application\ApplicationController@applicationList')
            ->name('api.students.applications-list');
});

Route::prefix('questionnaire')->middleware('auth:api')->group(function () {
    // 问卷调查列表
    Route::get('/index', 'Api\Questionnaire\QuestionnaireController@index')
        ->name('api.questionnaire.index');
    //问卷调查投票
    Route::get('/vote/{id}', 'Api\Questionnaire\QuestionnaireController@vote')
        ->name('api.questionnaire.vote');
});

// 最新版本号
Route::prefix('version')->group(function () {
    Route::get('/index', 'Api\Version\VersionController@index')->name('api.version.index');
});

// APP 首页接口
Route::prefix('home')->middleware('auth:api')->group(function () {
    //
    Route::any('/getHomePageInfo', 'Api\Home\IndexController@index')
        ->name('api.home.index');
    // 校园动态
    Route::any('/newsPage', 'Api\Home\IndexController@newsPage')
        ->name('api.home.newsPage');

    Route::any('/load-news', 'Api\Home\IndexController@loadNews')
        ->name('api.home.load-news');

    Route::any('/load-notices', 'Api\Home\IndexController@loadNotices')
        ->name('api.home.load-notices');
});

// 消息通知
Route::prefix('notice')->middleware('auth:api')->group(function () {
    // 通知列表
    Route::post('/notice-list', 'Api\Notice\NoticeController@getNotice')
        ->name('api.notice.list');
    // 通知详情
    Route::post('/notice-info', 'Api\Notice\NoticeController@noticeInfo')
    ->name('api.notice.info');
    // 发布通知
    Route::post('/issue-notice', 'Api\Notice\NoticeController@issueNotice')
        ->name('api.notice.issue-notice');
});

// APP banner 接口
Route::prefix('banner')->middleware('auth:api')->group(function () {
    Route::post('/getBanner', 'Api\Home\IndexController@banner')->name('api.banner.index');
});

// APP 生成二维码 接口
Route::prefix('QrCode')->middleware('auth:api')->group(function () {
    // 生成学生端二维码
    Route::post('/getQrCode', 'Api\QrCode\IndexController@generate')->name('api.generate.qr.code');
    // 上课补签二维码
    Route::post('/courseQrCode', 'Api\QrCode\teacherSign@courseQrCode')->name('api.course.qr.code');
    // 扫码 个人信息
    Route::post('/information', 'Api\QrCode\IndexController@information')->name('api.course.qr.information');
});

Route::prefix('account')->middleware('auth:api')->group(function () {
    Route::post('/getAccountCore', 'Api\Account\IndexController@index')->name('api.account.core');
});

Route::prefix('pipeline')->middleware('auth:api')->group(function (){
    /**
     * 用户调取可用的流程集合的接口
     */
    Route::any('/flows/my', 'Api\Pipeline\FlowsController@my')
        ->name('api.pipeline.flows.my');

    /**
     * 用户调取自己发起的申请的接口
     */
    Route::post('/flows/started-by-me', 'Api\Pipeline\FlowsController@started_by_me')
        ->name('api.pipeline.flows.started-by-me');

    /**
     * 用户调取正在等待自己审核的申请
     */
    Route::post('/flows/waiting-for-me', 'Api\Pipeline\FlowsController@waiting_for_me')
        ->name('api.pipeline.flows.waiting-for-me');

    /**
     * 用户调取可用的流程集合的接口
     */
    Route::post('/flow/open', 'Api\Pipeline\FlowsController@open')
        ->name('api.pipeline.flow.open');

    Route::post('/flow/start', 'Api\Pipeline\FlowsController@start')
        ->name('api.pipeline.flow.start');

    Route::post('/flow/process', 'Api\Pipeline\FlowsController@process')
        ->name('api.pipeline.flow.process');

    Route::post('/flow/resume', 'Api\Pipeline\FlowsController@resume')
        ->name('api.pipeline.flow.resume');

    Route::post('/flow/watch', 'Api\Pipeline\FlowsController@watch')
        ->name('api.pipeline.flow.watch');

    Route::post('/flow/cancel-action', 'Api\Pipeline\FlowsController@cancel_action')
        ->name('api.pipeline.flow.cancel-action');

    Route::post('/flow/view-action', 'Api\Pipeline\FlowsController@view_action')
        ->name('api.pipeline.flow.view-action');
});

Route::prefix('notification')->middleware('auth:api')->group(function () {
    // 消息中心
    Route::any('/list','Api\Notice\SystemNotificationController@index')
        ->name('api.notification.list');
});

Route::prefix('attendance')->middleware('auth:api')->group(function () {
    // 值周
    Route::any('/list','Api\AttendanceSchedule\AttendanceScheduleController@display')
        ->name('api.attendance.list');
    Route::any('/load-special','Api\AttendanceSchedule\AttendanceScheduleController@load_special')
        ->name('api.attendance.load-special');

    // 学生签到
    Route::post('/sign-in-record','Api\AttendanceSchedule\AttendanceController@signInRecord')
        ->name('api.attendance.sign-in-record');
    // 签到详情
    Route::post('/sign-in-details','Api\AttendanceSchedule\AttendanceController@signInDetails')
        ->name('api.attendance.sign-in-details');
    // 添加旷课记录
    Route::post('/add-truant-record','Api\AttendanceSchedule\AttendanceController@addTruangrade-signtRecord')
        ->name('api.attendance.add-truant-record');

   /* // 教师端 班级签到--所有班级
    Route::get('/grade-sign','Api\AttendanceSchedule\AttendanceController@gradeSign')
        ->name('api.attendance.grade-sign');
    // 教师端 班级签到--课程列表
    Route::post('/course-sign','Api\AttendanceSchedule\AttendanceController@courseSign')
        ->name('api.attendance.course-sign');*/

    // 开启补签
    Route::post('/start-supplement', 'Api\AttendanceSchedule\AttendanceController@startSupplement')
        ->name('api.start.supplement');

    // 教师扫云班牌二维码
    Route::post('/teacher-sweep-qr-code', 'Api\AttendanceSchedule\AttendanceController@teacherSweepQrCode')
        ->name('api.teacher.sweep.code');

     // 学生扫云班牌二维码
    Route::post('/student-sweep-qr-code', 'Api\AttendanceSchedule\AttendanceController@studentSweepQrCode')
        ->name('api.student.sweep.code');

    // 教师上个课签到
    Route::post('/teacher-course-sign', 'Api\AttendanceSchedule\AttendanceController@teacherSign')
        ->name('api.teacher.course.sign');

    /**
     * 教师考勤
     */
    // 获取当天所有课节
    Route::post('/get-day-course', 'Api\AttendanceSchedule\AttendanceController@getDayCourse')
        ->name('api.get.day.course');

    // 获取教师签到统计
    Route::post('/get-teacher-statistics', 'Api\AttendanceSchedule\add-messageadd-message@getTeacherCourseStatistics')
        ->name('api.get.teacher.statistics');

    // 获取教师签到统计详情
    Route::post('/get-teacher-sign-details', 'Api\AttendanceSchedule\AttendanceController@teacherSignDetails')
        ->name('api.get.teacher.statistics.info');
});

Route::prefix('user')->group(function () {
    // 登录
    Route::post('/login', 'Api\Login\LoginController@index')
        ->name('api.user.login');
    // 退出
    Route::post('/logout', 'Api\Login\LoginController@logout')
        ->middleware('auth:api')->name('api.user.logout');
    // 修改密码
    Route::post('/editUserPasswordInfo', 'Api\Login\LoginController@editPassword')
        ->middleware('auth:api')->name('api.user.edit.password');
    // 忘记密码
    Route::post('/findUserPasswordInfo', 'Api\Login\LoginController@forgetPassword')
        ->name('api.user.edit.password');
    // 学生用户资料
    Route::post('/getUserInfo', 'Api\Home\IndexController@getUserInfo')
        ->middleware('auth:api')->name('api.get.user.info');
    // 教师用户资料
    Route::post('/getTeacherInfo', 'Api\Home\IndexController@getTeacherInfo')
        ->middleware('auth:api')->name('api.get.teacher.info');
    // 修改资料
    Route::post('/updateUserInfo', 'Api\Home\IndexController@updateUserInfo')
        ->middleware('auth:api')->name('api.update.user.info');
    // 修改用户手机号
    Route::post('/updateUserMobileInfo', 'Api\Login\LoginController@updateUserMobileInfo')
        ->middleware('auth:api')->name('api.update.user.mobile');
    // 意见反馈
    Route::post('/proposal', 'Api\Home\IndexController@proposal')
        ->middleware('auth:api')->name('api.user.proposal');
    // 反馈列表
    Route::post('/proposal-list', 'Api\Home\IndexController@proposalList')
        ->middleware('auth:api')->name('api.user.proposal.list');

});


// 发送短信
Route::post('/index/sms', 'Api\Home\IndexController@sendSms')
    ->name('api.user.send.sms');


// 地区列表
Route::prefix('location')->group(function () {
    // 省份列表
    Route::get('/get-provinces','Api\Location\AreaController@getProvinces')
        ->name('api.location.get-provinces');
    // 城市列表
    Route::post('/get-cities','Api\Location\AreaController@getCities')
        ->name('api.location.get-cities');
    // 区县列表
    Route::post('/get-districts','Api\Location\AreaController@getDistricts')
        ->name('api.location.get-districts');
});

// 会议管理
Route::prefix('conferences')->middleware('auth:api')->group(function () {
    // 签到
    Route::get('/sign-in','Api\Conferences\ConferenceController@signIn')
        ->name('api.conferences.sign-in');
    // 待完成
    Route::get('/unfinished','Api\Conferences\ConferenceController@unfinished')
        ->name('api.conferences.unfinished');
    // 已完成
    Route::get('/accomplish','Api\Conferences\ConferenceController@accomplish')
        ->name('api.conferences.accomplish');
    // 自己创建的
    Route::get('/oneselfCreate','Api\Conferences\ConferenceController@oneselfCreate')
        ->name('api.conferences.oneselfCreate');
    // 会议详情
    Route::post('/conference-info','Api\Conferences\ConferenceController@conferenceInfo')
        ->name('api.conferences.conferenceInfo');
});

// 项目管理
Route::prefix('oa')->middleware('auth:api')->group(function () {
    // 创建项目
    Route::post('/create-project','Api\OA\ProjectsController@createProject')
        ->name('api.oa.create-project');
    // 创建任务
    Route::post('/create-task','Api\OA\ProjectsController@createTask')
        ->name('api.oa.create-task');
    // 创建任务讨论
    Route::post('/create-discussion','Api\OA\ProjectsController@createDiscussion')
        ->name('api.oa.create-discussion');
    // 项目列表
    Route::get('/project-list','Api\OA\ProjectsController@projectList')
        ->name('api.oa.project-list');
    // 项目下的任务列表
    Route::get('/task-list','Api\OA\ProjectsController@taskList')
        ->name('api.oa.task-list');
    // 任务下的评论列表
    Route::get('/discussion-list','Api\OA\ProjectsController@discussionList')
        ->name('api.oa.discussion-list');
    // 项目详情
    Route::get('/project-info','Api\OA\ProjectsController@projectInfo')
        ->name('api.oa.project-info');
    // 项目详情修改
    Route::post('/update-project','Api\OA\ProjectsController@updateProject')
        ->name('api.oa.update-project');
});

// 社区
Route::prefix('forum')->middleware('auth:api')->group(function () {
    Route::post('/comments','Api\Forum\ForumCommentController@getComments')
        ->name('api.forum.comments');
    Route::post('/comments/addcomment','Api\Forum\ForumCommentController@addComment')
        ->name('api.forum.comments/addcomment');
    Route::post('/comments/addreply','Api\Forum\ForumCommentController@addCommentReply')
        ->name('api.forum.comments/addreply');
    Route::get('/comments/addlike/{id}','Api\Forum\ForumCommentController@addLike')
        ->name('api.forum.comments/addlike');
    Route::get('/comments/dellike/{id}','Api\Forum\ForumCommentController@delLike')
        ->name('api.forum.comments/dellike');

    // 发帖
    Route::post('/posted','Api\Forum\ForumController@index')
        ->name('api.add.posted.forum');
    // 列表
    Route::post('/list','Api\Forum\ForumController@list')
        ->name('api.list.forum');
    // 详情
    Route::post('/details','Api\Forum\ForumController@details')
        ->name('api.details.forum');
    Route::post('/community/approve','Api\Forum\CommunityController@approve')
        ->name('api.forum.community.approve');
    Route::get('/community/joinlist','Api\Forum\CommunityController@joinCommunityList')
        ->name('api.forum.community.joinlist');//团长可以看自己的社团申请列表
    Route::get('/community/join','Api\Forum\CommunityController@joinCommunity')
        ->name('api.forum.community.join');//申请加入一个社群
    Route::get('/community/reject','Api\Forum\CommunityController@rejectCommunity')
        ->name('api.forum.community.reject');//团长拒绝用户加入
    Route::get('/community/accept','Api\Forum\CommunityController@acceptCommunity')
        ->name('api.forum.community.accept');//团长同意用户加入
    Route::get('/community/communities','Api\Forum\CommunityController@getCommunities')
        ->name('api.forum.community.communities');
    Route::get('/community/community/{id}','Api\Forum\CommunityController@getCommunity')
        ->name('api.forum.community.community');
});
// 社区
Route::prefix('social')->middleware('auth:api')->group(function () {
    Route::get('/follow','Api\Forum\CommunityController@followUser')
        ->name('api.social.follow');
    Route::get('/unfollow','Api\Forum\CommunityController@unFollowUser')
        ->name('api.social.unfollow');
    Route::get('/like','Api\Forum\CommunityController@like')
        ->name('api.social.like');
    Route::get('/unlike','Api\Forum\CommunityController@unlike')
        ->name('api.social.unlike');
});

// 个人评价模块的接口
Route::prefix('evaluate')->middleware('auth:api')->group(function () {
    // 学生评价老师的接口
    Route::any('/student/rate-lesson','Api\Evaluate\RatingController@rate_lesson')
        ->name('api.evaluate.student.rate-lesson');
    // 学生写课堂笔记
    Route::any('/student/save-note','Api\Evaluate\RatingController@save_note')
        ->name('api.evaluate.student.save-note');


//    // 评教老师列表
//    Route::post('/record/teacher-list','Api\Evaluate\EvaluateTeacherRecordController@getTeacherList')
//        ->name('api.evaluate.record.teacher-list');
    // 评教模版
    Route::get('/template','Api\Evaluate\EvaluateTeacherRecordController@template')
        ->name('api.evaluate.template');
    // 评教接口
    Route::post('/evaluate-teacher','Api\Evaluate\EvaluateTeacherRecordController@save_evaluate')
        ->name('api.evaluate.create');
//    // 是否开启评教
//    Route::get('/record/isEvaluate','Api\Evaluate\EvaluateTeacherRecordController@isEvaluate')
//        ->name('api.evaluate.record.isEvaluate');
});

Route::prefix('cloud')->group(function () {
    // 获取学校信息
    Route::post('/getSchoolInfo','Api\Cloud\CloudController@getSchoolInfo')
        ->name('api.cloud.school');
    // 获取班级信息
    Route::post('/getGradesInfo','Api\Cloud\CloudController@getGradesInfo')
        ->name('api.cloud.grade');
    // 获取课程信息
    Route::post('/getCourseInfo','Api\Cloud\CloudController@getCourseInfo')
        ->name('api.cloud.course');
    // 签到二维码
    Route::post('/getQrCode','Api\Cloud\CloudController@getQrCode')
        ->name('api.cloud.qr.code');
    // 考勤统计
    Route::post('/getAttendanceStatistic','Api\Cloud\CloudController@getAttendanceStatistic')
        ->name('api.cloud.attendance.statistic');
    // 接收华三考勤数据
    Route::post('/distinguish','Api\Cloud\CloudController@distinguish')
        ->name('api.cloud.distinguish.data');
     // 华三人脸识别图片上传
    Route::post('/uploadFaceImage','Api\Cloud\CloudController@uploadFaceImage')
        ->name('api.cloud.upload.face.image')->middleware('auth:api');
});


Route::prefix('Oa')->middleware('auth:api')->group(function(){
    // 党员活动
    Route::post('/activity/getCpcActivityListInfo','Api\OA\IndexController@activity')
        ->name('api.cloud.school');
    // 党员学习
    Route::post('/study/getCpcStudyListInfo','Api\OA\IndexController@study')
        ->name('api.cloud.grade');
    // 党员风采
    Route::post('/zone/getCpcZoneListInfo','Api\OA\IndexController@zone')
        ->name('api.cloud.course');
});

Route::post('/manual/attendances','Api\Cloud\CloudController@manual')
        ->middleware('auth:api')->name('api.manual.attendances');


// 课件
Route::prefix('learn')->middleware('auth:api')->group(function(){
    // 收藏列表
    Route::post('/courseWare/getCollectionList','Api\Course\CourseWareController@collectionList')
        ->name('api.learn.collection.list');
    // 课件信息
    Route::post('/courseWare/getCourseInfo','Api\Course\CourseWareController@courseInfo')
        ->name('api.learn.course.info');
    // 上课资料列表
    Route::post('/courseWare/getCourseWareData','Api\Course\CourseWareController@courseWareData')
        ->name('api.learn.course.ware');
    // 课件列表
    Route::post('/courseWare/getCourseWareList','Api\Course\CourseWareController@courseWareList')
        ->name('api.learn.course.ware.list');
    //
    Route::post('/courseWare/getDownloadList','Api\Course\CourseWareController@downloadList')
        ->name('api.learn.download.list');
});

// 课件: 此课件接口模块的创建者为 Yue Wang, 采用的是 course -> lecture -> lecture's materials 的数据结构，与上面的课件是不同的
Route::prefix('course')->middleware('auth:api')->group(function(){
    // 获取某课节的详情
    Route::post('/teacher/load-lecture','Api\Course\Lecture\LecturesController@load_lecture')
        ->name('api.course.teacher.load-lecture');
    // 获取某课节所包含的课件记录集合
    Route::post('/teacher/lecture/load-materials','Api\Course\Lecture\LecturesController@load_lecture_materials')
        ->name('api.course.teacher.lecture.load-materials');

    // 学生获取某个课节的作业
    Route::post('/student/load-homework','Api\Course\Lecture\LecturesController@load_student_homework')
        ->name('api.course.student.load-homework');
    // 学生提交作业
    Route::post('/student/save-homework','Api\Course\Lecture\LecturesController@save_homework')
        ->name('api.course.student.save-homework');
    Route::post('/student/delete-homework','Api\Course\Lecture\LecturesController@delete_homework')
        ->name('api.course.student.delete-homework');
});

Route::prefix('teacher')->middleware('auth:api')->group(function(){
    // 教师添加访客
    Route::post('/add-visitor','Api\OA\TeachersController@add_visitor')
        ->name('api.teacher.add-visitor');
    Route::post('/delete-visitor','Api\OA\TeachersController@delete_visitor')
        ->name('api.teacher.delete-visitor');
});


Route::prefix('code')->middleware('auth:api')->group(function(){
    // 添加二维码记录
    Route::post('/create-record','Api\QrCode\IndexController@createRecord')
        ->name('api.code.create-record');
});

// 教师评教
Route::prefix('teacher/evaluation')->middleware('auth:api')->group(function(){
    // 是否开启评学
    Route::post('/is-start','Api\Evaluate\TeacherEvaluationController@isEvaluation')
        ->name('api.teacher.evaluation.is.start');
    // 教师教过的所有班级
    Route::post('/grade-list','Api\Evaluate\TeacherEvaluationController@index')
        ->name('api.teacher.evaluation.grade.list');
    // 所有学生
    Route::post('/grade-student','Api\Evaluate\TeacherEvaluationController@student')
        ->name('api.teacher.evaluation.grade.student');
    // 评学模板
    Route::post('/template','Api\Evaluate\TeacherEvaluationController@template')
        ->name('api.teacher.evaluation.template');
    // 评价学生
    Route::post('/student','Api\Evaluate\TeacherEvaluationController@students')
        ->name('api.teacher.evaluation.students');

    // 教师提交自己评教的业绩材料: 王越添加
    Route::post('/save-qualification','Api\Evaluate\TeacherEvaluationController@save_qualification')
        ->name('api.teacher.save.qualification'); // 保存业绩材料
    Route::post('/load-qualifications','Api\Evaluate\TeacherEvaluationController@load_qualifications')
        ->name('api.teacher.load.qualifications');// 加载业绩材料
});


Route::prefix('Oa')->middleware('auth:api')->group(function () {
    /**
     * 内部信
     */
    // 获取所有老师
    Route::post('/get-teachers','Api\OA\InternalMessageController@getTeachers')
        ->name('api.oa.get.teachers');
    // 发信
    Route::post('/add-message','Api\OA\InternalMessageController@addMessage')
        ->name('api.oa.add.message');
    // 内部信列表
    Route::post('/message-list','Api\OA\InternalMessageController@messageList')
        ->name('api.oa.add.message');
    // 信件详情
    Route::post('/message-info','Api\OA\InternalMessageController@massageInfo')
        ->name('api.oa.add.message');
    // 删除 or 更新已读
    Route::post('/message-update-or-del','Api\OA\InternalMessageController@updateOrDelMessage')
        ->name('api.oa.update.or.del.message');
    // 更新信件
    Route::post('/message-update','Api\OA\InternalMessageController@messageUpdate')
        ->name('api.oa.update.message');
    // 上传附件
    Route::post('/message-upload-files', 'Api\OA\InternalMessageController@uploadFiles')
        ->name('api.oa.upload.files');


    /**
     * 工作日志
     */
    // 添加
    Route::post('/add-work-log', 'Api\OA\WorkLogController@index')
        ->name('api.oa.add.work.log');
    // 列表
    Route::post('/list-work-log', 'Api\OA\WorkLogController@workLogList')
        ->name('api.oa.work.log.list');
    // 详情
    Route::post('/work-log-info', 'Api\OA\WorkLogController@workLogInfo')
        ->name('api.oa.work.log.info');
    // 发送
    Route::post('/work-log-send', 'Api\OA\WorkLogController@workLogSend')
        ->name('api.oa.work.log.send');
    // 更新
    Route::post('/update-work-log', 'Api\OA\WorkLogController@workLogUpdate')
        ->name('api.oa.work.log.send');
    Route::post('/delete-work-log', 'Api\OA\WorkLogController@workLogDel')
        ->name('api.oa.work.log.send');
    /**
     * 助手页
     */
    Route::post('/helper-page', 'Api\OA\IndexController@helperPage')
        ->name('api.oa.helper.page');

    /**
     * 班级管理
     */
    // 获取班级风采
    Route::post('/grade-resources', 'Api\OA\GradeManageController@index')
        ->name('api.oa.grade.resources');
    // 上传班级风采
    Route::post('/upload-grade-resources', 'Api\OA\GradeManageController@uploadGradeResource')
        ->name('apigetQrCode.oa.upload.grade.resources');
    // 删除班级风采
    Route::post('/del-grade-resources', 'Api\OA\GradeManageController@delGradeResource')
        ->name('api.oa.del.grade.resources');
    // 班级列表
    Route::post('/grade-list', 'Api\OA\GradeManageController@gradesList')
        ->name('api.oa.grade.list');
    // 学生列表
    Route::post('/student-list', 'Api\OA\GradeManageController@studentList')
        ->name('api.oa.student.list');
    // 学生详情
    Route::post('/student-info', 'Api\OA\GradeManageController@studentInfo')
        ->name('api.oa.student.info');
    // 修改学生信息
    Route::post('/update-student-info', 'Api\OA\GradeManageController@updateStudentInfo')
        ->name('api.oa.update.student.info');

});


// 所见即所得编辑器的文件和图片上传接口
Route::prefix('wysiwyg')->group(function () {
    Route::any('/files/upload', 'Api\Wysiwyg\FilesController@files_upload')
        ->name('api.wysiwyg.files.upload');
    Route::any('/files/view', 'Api\Wysiwyg\FilesController@files_view')
        ->name('api.wysiwyg.files.view');
    Route::any('/images/upload', 'Api\Wysiwyg\FilesController@images_upload')
        ->name('api.wysiwyg.images.upload');
    Route::any('/images/view', 'Api\Wysiwyg\FilesController@images_view')
        ->name('api.wysiwyg.images.view');
});

Route::prefix('signInGrade')->middleware('auth:api')->group(function () {

    // 全部记录的筛选
    Route::get('/timeScreen', 'Api\AttendanceSchedule\SignInGradeController@timeScreen')
        ->name('api.signInGrade.timeScreen');
    Route::get('/classList', 'Api\AttendanceSchedule\SignInGradeController@courseClassList')
        ->name('api.signInGrade.classList');
    // 签到详情
    Route::post('/signDetails','Api\AttendanceSchedule\SignInGradeController@signDetails')
        ->name('api.signInGrade.signDetails');
    // 保存签到详情
    Route::post('/saveDetails','Api\AttendanceSchedule\SignInGradeController@saveDetails')
        ->name('api.signInGrade.saveDetails');
    // 保存评分
    Route::post('/saveScore','Api\AttendanceSchedule\SignInGradeController@saveScore')
        ->name('api.signInGrade.saveScore');
    // 全部记录的课程列表
    Route::post('/signInCourses','Api\AttendanceSchedule\SignInGradeController@signInCourses')
        ->name('api.signInGrade.signInCourses');
    // 班级内学生签到列表
    Route::post('/signInStudentList','Api\AttendanceSchedule\SignInGradeController@signInStudentList')
        ->name('api.signInGrade.signInStudentList');
    // 备注列表
    Route::post('/remarkList','Api\AttendanceSchedule\SignInGradeController@remarkList')
        ->name('api.signInGrade.remarkList');

    // 当前班主任-班级列表
    Route::get('/gradeList', 'Api\AttendanceSchedule\SignInGradeController@gradeList')
        ->name('api.signInGrade.gradeList');
    // 班级签到
    Route::post('/gradeSignIn', 'Api\AttendanceSchedule\SignInGradeController@gradeSignIn')
        ->name('api.signInGrade.gradeSignIn');
    // 班级签到-详情
    Route::post('/gradeSignIn-details', 'Api\AttendanceSchedule\SignInGradeController@gradeSignInDetails')
        ->name('api.signInGrade.gradeSignIn-details');
    // 今日评分 todayGrade
    Route::post('/todayGrade', 'Api\AttendanceSchedule\SignInGradeController@todayGrade')
        ->name('api.signInGrade.todayGrade');
    // 评分详情
    Route::post('/gradeDetails', 'Api\AttendanceSchedule\SignInGradeController@gradeDetails')
        ->name('api.signInGrade.gradeDetails');
});

// 校园风光
Route::prefix('campus')->group(function () {
    Route::any('/scenery', 'Api\School\CampusController@scenery')
        ->name('api.campus.scenery');
});

// 科研成果.
Route::prefix('campus')->middleware('auth:api')->group(function () {
    Route::post('/scientific', 'Api\School\CampusController@scientific')
        ->name('api.campus.scientific');
});

// 可见范围选择器专用
Route::prefix('organizations')->middleware('auth:api')->group(function(){
    Route::any('/load-by-roles', 'Api\School\OrganizationController@load_by_roles')
        ->name('api.organizations.load-by-roles');
});


// 学习
Route::prefix('study')->middleware('auth:api')->group(function(){
    // 学习首页
    Route::any('/home-page', 'Api\Study\IndexController@index')
        ->name('api.study.home-page');
    // 学习类型列表
    Route::any('/type-list', 'Api\Study\IndexController@materialType')
        ->name('api.study.type-list');
    // 学生端资料列表
    Route::any('/material-list', 'Api\Study\IndexController@materialList')
        ->name('api.study.material-list');
    // 教师端课程列表
    Route::any('/course-list', 'Api\Study\IndexController@courseList')
        ->name('api.study.course-list');
    // 教师端资料列表
    Route::any('/course-material-list', 'Api\Study\IndexController@courseMaterialList')
        ->name('api.study.course-material-list');
    // 删除学习资料
    Route::any('/delete-material', 'Api\Study\IndexController@deleteMaterial')
        ->name('api.study.delete-material');

    // 学生端课表
    Route::any('/timetable-student', 'Api\Study\TimetableController@student')
        ->name('api.study.timetable-student');
    // 教师端课表
    Route::any('/timetable-teacher', 'Api\Study\TimetableController@teacher')
        ->name('api.study.timetable-teacher');
    // 课程表详情
    Route::any('/timetable-details', 'Api\Study\TimetableController@timetableDetails')
        ->name('api.study.timetable-details');
});


// 新的会议
Route::prefix('meeting')->middleware('auth:api')->group(function(){
    // 会议设置
    Route::get('/meeting-set', 'Api\OA\NewMeetingController@meetingSet')
        ->name('api.meeting.meeting-set');
    // 创建会议
    Route::post('/addMeeting','Api\OA\NewMeetingController@addMeeting')
        ->name('api.meeting.addMeeting');
    // 待完成
    Route::get('/unfinished','Api\OA\NewMeetingController@unfinished')
        ->name('api.meeting.unfinished');
    // 已完成
    Route::get('/accomplish','Api\OA\NewMeetingController@accomplish')
        ->name('api.meeting.accomplish');
    // 自己创建的
    Route::get('/oneselfCreate','Api\OA\NewMeetingController@oneselfCreate')
        ->name('api.meeting.oneselfCreate');
    // 会议详情
    Route::get('/meetDetails','Api\OA\NewMeetingController@meetDetails')
        ->name('api.meeting.meetDetails');
    // 待完成-会议签到
    Route::get('/meetSignIn','Api\OA\NewMeetingController@meetSignIn')
        ->name('api.meeting.meetSignIn');
    // 保存签到签退
    Route::get('/saveSignIn','Api\OA\NewMeetingController@saveSignIn')
        ->name('api.meeting.saveSignIn');
    // 已完成-获取会议纪要
    Route::get('/getMeetSummary','Api\OA\NewMeetingController@getMeetSummary')
        ->name('api.meeting.getMeetSummary');
    // 已完成-保存会议纪要
    Route::post('/saveMeetSummary','Api\OA\NewMeetingController@saveMeetSummary')
        ->name('api.meeting.saveMeetSummary');
    // 已完成-签到记录
    Route::get('/signInRecord','Api\OA\NewMeetingController@signInRecord')
        ->name('api.meeting.signInRecord');
    // 签到二维码
    Route::get('/signInQrCode','Api\OA\NewMeetingController@signInQrCode')
        ->name('api.meeting.signInQrCode');
    // 签退二维码
    Route::get('/signOutQrCode','Api\OA\NewMeetingController@signOutQrCode')
        ->name('api.meeting.signOutQrCode');
    // 我创建的-会议纪要
    Route::get('/myMeetSummary','Api\OA\NewMeetingController@myMeetSummary')
        ->name('api.meeting.myMeetSummary');
    // 我创建的-签到记录
    Route::get('/mySignInRecord','Api\OA\NewMeetingController@mySignInRecord')
        ->name('api.meeting.mySignInRecord');
});

// PC办公页
Route::prefix('office')->middleware('auth:api')->group(function(){
        Route::any('/office-page', 'Admin\IndexController@officeIcon')
        ->name('api.office.office-page');
        Route::any('/help-page', 'Admin\IndexController@helpIcon')
        ->name('api.office.help-page');
});
