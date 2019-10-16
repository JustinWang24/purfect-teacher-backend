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


Auth::routes();

// 主页
Route::get('/home', 'HomeController@index')->name('home');

// 校园风光
//Route::get('/scenery', 'Admin\CampusSceneryController@index')->name('home');




//招生管理
Route::prefix('recruitStu')->group(function () {
    Route::get('index', 'RecruitStuController@index')->name('recruitStu.index');
});


//迎新管理
Route::prefix('welcomeNewStu')->group(function () {
    Route::get('index', 'WelcomeNewStuController@index')->name('welcomeNewStu.index');
});


//离校管理
Route::prefix('leaveSchool')->group(function () {
    Route::get('index', 'LeaveSchoolController@index')->name('leaveSchool.index');
});


//内容管理
Route::prefix('content')->group(function () {
    Route::get('index', 'ContentController@index')->name('content.index');
});


//教务管理
Route::prefix('teachingTask')->group(function () {
    Route::get('index', 'TeachingTaskController@index')->name('teachingTask.index');
});


//教学管理
Route::prefix('teaching')->group(function () {
    Route::get('index', 'TeachingController@index')->name('teaching.index');
});


//办公管理
Route::prefix('work')->group(function () {
    Route::get('index', 'WorkController@index')->name('work.index');
});


//设备管理
Route::prefix('equipment')->group(function () {
    Route::get('index', 'EquipmentController@index')->name('equipment.index');
});


//报修管理
Route::prefix('repair')->group(function () {
    Route::get('index', 'RepairController@index')->name('repair.index');
});


//账户管理
Route::prefix('account')->group(function () {
    Route::get('index', 'SchoolSceneryController@index')->name('account.index');
});


//就业管理
Route::prefix('getJob')->group(function () {
    Route::get('index', 'GetJobController@index')->name('getJob.index');
});


//培训管理
Route::prefix('train')->group(function () {
    Route::get('index', 'TrainController@index')->name('train.index');
});


//校园网管理
Route::prefix('schoolWeb')->group(function () {
    Route::get('index', 'SchoolWebController@index')->name('schoolWeb.index');
});


//电商管理
Route::prefix('ec')->group(function () {
    Route::get('index', 'EcController@index')->name('ec.index');
});


//社区管理
Route::prefix('community')->group(function () {
    Route::get('index', 'CommunityController@index')->name('community.index');
});


//优惠管理
Route::prefix('discount')->group(function () {
    Route::get('index', 'DiscountController@index')->name('discount.index');
});


//会员管理
Route::prefix('vip')->group(function () {
    Route::get('index', 'VipController@index')->name('vip.index');
});


//学生币管理
Route::prefix('stuMoney')->group(function () {
    Route::get('index', 'StuMoneyController@index')->name('stuMoney.index');
});


//客服管理
Route::prefix('customerService')->group(function () {
    Route::get('index', 'CustomerServiceController@index')->name('customerService.index');
});


//资源位管理
Route::prefix('resourcesBit')->group(function () {
    Route::get('index', 'ResourcesBitController@index')->name('resourcesBit.index');
});

//用户管理
Route::prefix('user')->group(function () {
    Route::get('index', 'UserController@index')->name('user.index');
});


//基础设置
Route::prefix('baseSet')->group(function () {
    Route::get('index', 'BaseSetController@index')->name('baseSet.index');
});


//数据管理
Route::prefix('data')->group(function () {
    Route::get('index', 'DataController@index')->name('data.index');
});


//日志管理
Route::prefix('log')->group(function () {
    Route::get('index', 'LogController@index')->name('log.index');
});


//版本管理
Route::prefix('version')->group(function () {
    Route::get('index', 'VersionController@index')->name('version.index');
});


//角色管理
Route::prefix('role')->group(function () {
    Route::get('index', 'RoleController@index')->name('role.index');
});


//账号管理
Route::prefix('accountNumber')->group(function () {
    Route::get('index', 'AccountNumberController@index')->name('accountNumber.index');
});






