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
});