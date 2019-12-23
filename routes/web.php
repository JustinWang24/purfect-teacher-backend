<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

/**
 * 无须登录的前端页面路由
 */
// 招生相关
Route::prefix('statics')->group(function () {
    Route::get('/school/majors/list', 'Statics\PagesController@school_majors_list')->name('static.school.majors-list');
});

Route::prefix('network-disk')->group(function () {
    // 上传文件
    Route::post('/media/upload','Api\NetworkDisk\MediaController@upload')
        ->name('file.manager.media.upload');
});

// APP嵌入的 H5 页面的集合
Route::prefix('h5')->group(function () {
    // 用户启动流程
    Route::any('/flow/user/start','H5\Pipeline\FlowsController@start')
        ->name('h5.flow.user.start');
    // 用户查看自己的流程列表
    Route::any('/flow/user/in-progress','H5\Pipeline\FlowsController@in_progress')
        ->name('h5.flow.user.in-progress');
    // 用户查看自己的流程历史详情
    Route::any('/flow/user/view-history','H5\Pipeline\FlowsController@view_history')
        ->name('h5.flow.user.view-history');
    // 学生查看自己今天的课表
    Route::any('/timetable/student/view','H5\Timetable\StudentController@view')
        ->name('h5.timetable.student.view');
    Route::any('/timetable/student/detail','H5\Timetable\StudentController@detail')
        ->name('h5.timetable.student.detail');

    // 教师端, 查看新闻等信息
    Route::any('/teacher/pages/view','H5\Teacher\PagesController@view')
        ->name('h5.teacher.pages.view');

    // APP 首页的动态新闻等的访问 URL
    Route::any('/teacher/news/view','H5\Teacher\PagesController@view_news')
        ->name('h5.teacher.news.view');

    // 教师端, 管理界面
    Route::any('/teacher/management/view','H5\Teacher\PagesController@management')
        ->name('h5.teacher.management.view');

    Route::any('/teacher/management/my-students','H5\Teacher\StudentsController@my_students')
        ->name('h5.teacher.management.my-students');

    // 教工助手
    Route::any('/teacher/management/devices-list','H5\Teacher\DevicesController@devices')
        ->name('h5.teacher.management.devices-list');
});

// 分享
Route::get('/share-file','Api\NetworkDisk\MediaController@shareFile')->name('shareFile');

Auth::routes();

// 主页
Route::get('/home', 'HomeController@index')->name('home');

// 校园风光
//Route::get('/scenery', 'Admin\CampusSceneryController@index')->name('home');

Route::prefix('pipeline')->group(function () {
    // 打开流程
    Route::get('/flow/open','Teacher\Pipeline\FlowsController@open')
        ->name('teacher.pipeline.flow-open');
    // 开始流程
    Route::get('/flow/start','Teacher\Pipeline\FlowsController@start')
        ->name('teacher.pipeline.flow-start');
    // 查看流程的历史
    Route::get('/flow/view-history','Teacher\Pipeline\FlowsController@view_history')
        ->name('pipeline.flow-view-history');

    // 学生专用
    Route::get('/flow/student/open','Teacher\Pipeline\FlowsController@open')
        ->name('student.pipeline.flow-open');
});


