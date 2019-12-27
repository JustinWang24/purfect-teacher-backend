<?php

Route::prefix('manager_wifi')->group(function ()
{
    // wifi产品
    Route::any('wifi/list', 'WifiController@list')->name('manager_wifi.wifi.list'); // wifi列表
    Route::any('wifi/add', 'WifiController@add')->name('manager_wifi.wifi.add'); // 添加wifi产品
    Route::any('wifi/edit', 'WifiController@edit')->name('manager_wifi.wifi.edit'); // 修改wifi产品

	// wifi订单
    Route::any('wifiOrder/list', 'WifiOrderController@list')->name('manager_wifi.wifiOrder.list'); // wifi订单列表
    Route::any('wifiOrder/detail', 'WifiOrderController@detail')->name('manager_wifi.wifiOrder.detail'); // wifi订单详情

	// wifi文档
    Route::any('wifiNotice/list', 'WifiNoticeController@list')->name('manager_wifi.wifiNotice.list'); // 列表
    Route::any('wifiNotice/add', 'WifiNoticeController@add')->name('manager_wifi.wifiNotice.add'); // 添加
    Route::any('wifiNotice/edit', 'WifiNoticeController@edit')->name('manager_wifi.wifiNotice.edit'); // 修改
    Route::any('wifiNotice/delete', 'WifiNoticeController@delete')->name('manager_wifi.wifiNotice.delete'); // 删除

	// wifi报修分类
    Route::any('wifiIssueType/list', 'WifiIssueTypeController@list')->name('manager_wifi.wifiIssueType.list'); // wifi报修分类
    Route::any('wifiIssueType/add', 'WifiIssueTypeController@add')->name('manager_wifi.wifiIssueType.add'); // 添加wifi报修分类
    Route::any('wifiIssueType/edit', 'WifiIssueTypeController@edit')->name('manager_wifi.wifiIssueType.edit'); // 修改wifi报修分类
    Route::any('wifiIssueType/delete', 'WifiIssueTypeController@delete')->name('manager_wifi.wifiIssueType.delete'); // 删除wifi报修分类

	// wifi报修
    Route::any('wifiIssue/list', 'WifiIssueController@list')->name('manager_wifi.wifiIssue.list'); // wifi报修列表
	Route::any('wifiIssue/edit', 'WifiIssueController@edit')->name('manager_wifi.wifiIssue.edit'); // wifi报修修改
    Route::any('wifiIssue/detail', 'WifiIssueController@detail')->name('manager_wifi.wifiIssue.detail'); // wifi报修详情
    Route::any('wifiIssue/update', 'WifiIssueController@update')->name('manager_wifi.wifiIssue.update'); // wifi报修处理
	
	// wifi报修评论
    Route::any('wifiIssueComment/list', 'WifiIssueCommentController@list')->name('manager_wifi.wifiIssueComment.list'); // wifi报修评论列表
    Route::any('wifiIssueComment/detail', 'WifiIssueCommentController@detail')->name('manager_wifi.wifiIssueComment.detail'); // wifi报修评论详情

   // wifi网络资讯表
   Route::any('wifiContent/list', 'WifiContentController@list')->name('manager_wifi.wifiContent.list'); // 列表
   Route::any('wifiContent/add', 'WifiContentController@add')->name('manager_wifi.wifiContent.add'); // 添加
   Route::any('wifiContent/edit', 'WifiContentController@edit')->name('manager_wifi.wifiContent.edit'); // 修改
   Route::any('wifiContent/delete', 'WifiContentController@delete')->name('manager_wifi.wifiContent.delete'); // 删除

   //  获取Json
   Route::any('WifiApi/get-school-campus', 'WifiApiController@get_school_campus')->name('manager_wifi.WifiApi.get_school_campus'); // 删除

});