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
});

// 招生API
Route::prefix('student-register')->middleware('auth:api')->group(function () {
     // 获取全部招生计划
     Route::post('/load-open-majors','Api\Recruitment\PlansController@load_plans')
        ->name('api.load.open.majors');

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
     // 加载所有的班级
     Route::post('/all-grades','Api\Address\AddressBookController@all_grades')
        ->name('api.school.all-grades');
});


Route::prefix('course')->middleware('auth:api')->group(function () {
     // 选修课列表
     Route::get('/elective/list','Api\Course\ElectiveController@index')
        ->name('api.course.elective.list');
     // 选课详情
     Route::get('/elective/details','Api\Course\ElectiveController@details')
        ->name('api.course.elective.details');
     // 选课报名操作
     Route::get('/elective/enroll/{id}','Api\Course\ElectiveController@enroll')
        ->name('api.course.elective.enroll');
     // 选课查询报名结果操作
     Route::get('/elective/getresult/{id}','Api\Course\ElectiveController@getEnrollResult')
        ->name('api.course.elective.getresult');
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
    Route::post('/getHomePageInfo', 'Api\Home\IndexController@index')->name('api.home.index');
});

// 消息通知
Route::prefix('notice')->middleware('auth:api')->group(function () {
    Route::post('/notice-list', 'Api\Notice\NoticeController@getNotice')
        ->name('api.notice.list');

    Route::post('/notice-info', 'Api\Notice\NoticeController@noticeInfo')
    ->name('api.notice.info');

});

// APP banner 接口
Route::prefix('banner')->middleware('auth:api')->group(function () {
    Route::post('/getBanner', 'Api\Home\IndexController@banner')->name('api.banner.index');
});

// APP 生成二维码 接口
Route::prefix('QrCode')->middleware('auth:api')->group(function () {
    Route::post('/getQrCode', 'Api\QrCode\IndexController@generate')->name('api.generate.qr.code');
});

Route::prefix('account')->middleware('auth:api')->group(function () {
    Route::post('/getAccountCore', 'Api\Account\IndexController@index')->name('api.account.core');
});

Route::prefix('pipeline')->middleware('auth:api')->group(function (){
    /**
     * 用户调取可用的流程集合的接口
     */
    Route::post('/flows/my', 'Api\Pipeline\FlowsController@my')
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
    Route::get('/list','Api\Notice\SystemNotificationController@index')
        ->name('api.notification.list');
});

Route::prefix('attendance')->middleware('auth:api')->group(function () {
    // 消息中心
    Route::get('/list','Api\AttendanceSchedule\AttendanceScheduleController@display')
        ->name('api.attendance.list');
});


Route::post('/user/login', 'Api\Login\LoginController@index')
        ->name('api.user.login');


// 地区列表
Route::prefix('location')->middleware('auth:api')->group(function () {
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




