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
    // 用户查看自己的流程
    Route::any('/flow/user/in-progress','H5\Pipeline\FlowsController@in_progress')
        ->name('h5.flow.user.in-progress');
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