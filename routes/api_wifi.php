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
Route::prefix('api_wifi')->middleware('auth:api')->group(function () {
    // 校园版首页wifi
    Route::any('/v1/wifi/index-wifi','\App\Http\Controllers\Api\Wifi\WifiController@index_wifi');
    // 校园网同意协议
    Route::any('/v1/wifi/edit-agreement-info','\App\Http\Controllers\Api\Wifi\WifiController@edit_agreement_info');	
	// 修改运营商信息
    Route::any('/v1/wifi/edit-operator-info','\App\Http\Controllers\Api\Wifi\WifiController@edit_operator_info');	

	// 无线充值上网首页
    Route::any('/v1/wifi_pay/index-recharge','\App\Http\Controllers\Api\Wifi\WifiPayController@index_recharge');
   // 充值无线
   Route::any('/v1/wifi_pay/pay-recharge-info','\App\Http\Controllers\Api\Wifi\WifiPayController@pay_recharge_info');
	// 无线充值明细
    Route::any('/v1/wifi_pay/list-recharge-info','\App\Http\Controllers\Api\Wifi\WifiPayController@list_recharge_info');
   // 检测是否在线
   Route::any('/v1/wifi_pay/check-wifi-status-info','\App\Http\Controllers\Api\Wifi\WifiPayController@check_wifi_status_info');
   // 上线
   Route::any('/v1/wifi_pay/up-wifi-status-info','\App\Http\Controllers\Api\Wifi\WifiPayController@up_wifi_status_info');
   // 下线
   Route::any('/v1/wifi_pay/down-wifi-status-info','\App\Http\Controllers\Api\Wifi\WifiPayController@down_wifi_status_info');

   // 有线上网首页
   Route::any('/v1/wifi_cable/index-cable','\App\Http\Controllers\Api\Wifi\WifiCableController@index_cable');
   // 宿舍楼地址列表
   Route::any('/v1/wifi_cable/list-category-info','\App\Http\Controllers\Api\Wifi\WifiCableController@list_category_info');
   // 通过地址获取已开通的端口
   Route::any('/v1/wifi_cable/list-open-cable-info','\App\Http\Controllers\Api\Wifi\WifiCableController@list_open_cable_info');
   // 开通有线
   Route::any('/v1/wifi_cable/add-cable-info','\App\Http\Controllers\Api\Wifi\WifiCableController@add_cable_info');

   // 获取报修类型
    Route::any('/v1/wifi_issue/list-category-info','\App\Http\Controllers\Api\Wifi\wifiIssueController@list_category_info');
	// 添加报修信息
    Route::any('/v1/wifi_issue/add-issue-info','\App\Http\Controllers\Api\Wifi\wifiIssueController@add_issue_info');
	// 获取我的报修列表
    Route::any('/v1/wifi_issue/list-my-issue-info','\App\Http\Controllers\Api\Wifi\wifiIssueController@list_my_issue_info');
	// 获取我的单个报修详情
    Route::any('/v1/wifi_issue/get-my-issue-info','\App\Http\Controllers\Api\Wifi\wifiIssueController@get_my_issue_info');
	// 评论单个报修
    Route::any('/v1/wifi_issue/add-issue-comment-info','\App\Http\Controllers\Api\Wifi\wifiIssueController@add_issue_comment_info');

   // 支付异步接口
   Route::any('/v1/wifi_pay/asyns-notice-info','\App\Http\Controllers\Api\Wifi\WifiPayController@asyns_notice_info');

});

// 常见问题
Route::get('/wifi/page-info','\App\Http\Controllers\Api\Wifi\WifiController@page_info');
Route::get('/wifi/page-view','\App\Http\Controllers\Api\Wifi\WifiController@page_view')->name('api.wifi.page_view');
