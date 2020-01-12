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
Route::prefix('api_welcome')->middleware('auth:api')->group(function () {
    // 迎新首页
    Route::any('/v1/welcome/index','\App\Http\Controllers\Api\Welcome\IndexController@index');
    // 个人信息(编辑页)
    Route::any('/v1/welcome/base-user-info','\App\Http\Controllers\Api\Welcome\IndexController@base_user_info');
    // 个人信息(编辑页)保存
    Route::any('/v1/welcome/save-user-info','\App\Http\Controllers\Api\Welcome\IndexController@save_user_info');
    // 保存用户照片
    Route::any('/v1/welcome/save-photo-info','\App\Http\Controllers\Api\Welcome\IndexController@save_photo_info');
    // 个人信息(已确认)
    Route::any('/v1/welcome/confirm-user-info','\App\Http\Controllers\Api\Welcome\IndexController@confirm_user_info');
    // 报到单
    Route::any('/v1/welcome/report-info','\App\Http\Controllers\Api\Welcome\IndexController@report_info');
    // 迎新指南
    Route::any('/v1/welcome/page-info','\App\Http\Controllers\Api\Welcome\IndexController@page_info');

});
