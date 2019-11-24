<?php

Route::prefix('manager_wifi')->group(function () {
    // wifi产品
    Route::any('wifi/list', 'WifiController@list')->name('manager_wifi.wifi.list'); // wifi列表
    Route::any('wifi/add', 'WifiController@add')->name('manager_wifi.wifi.add'); // 添加wifi产品
    Route::any('wifi/edit', 'WifiController@edit')->name('manager_wifi.wifi.edit'); // 修改wifi产品

	// wifi订单
    Route::any('wifiOrder/list', 'WifiOrderController@list')->name('manager_wifi.wifiOrder.list'); // wifi订单列表
    Route::any('wifiOrder/detail', 'WifiOrderController@edit')->name('manager_wifi.wifiOrder.detail'); // wifi订单详情

	// wifi文档
    Route::any('wifiNotice/list', 'WifiNoticeController@list')->name('manager_wifi.wifiNotice.list'); // 列表
    Route::any('wifiNotice/add', 'WifiNoticeController@add')->name('manager_wifi.wifiNotice.detail'); // 添加
    Route::any('wifiNotice/edit', 'WifiNoticeController@edit')->name('manager_wifi.wifiNotice.detail'); // 修改
	
	// wifi报修分类
    Route::any('WifiIssueType/list', 'WifiIssueTypeController@list')->name('manager_wifi.WifiIssueType.list'); // wifi报修分类
    Route::any('WifiIssueType/add', 'WifiIssueTypeController@add')->name('manager_wifi.WifiIssueType.add'); // 添加wifi报修分类
    Route::any('WifiIssueType/edit', 'WifiIssueTypeController@edit')->name('manager_wifi.WifiIssueType.edit'); // 修改wifi报修分类
	
	// wifi报修
    Route::any('WifiIssue/list', 'WifiIssueController@list')->name('manager_wifi.WifiIssue.list'); // wifi报修列表
    Route::any('WifiIssue/add', 'WifiIssueController@detail')->name('manager_wifi.WifiIssue.detail'); // wifi报修详情
	
	// wifi报修评论
    Route::any('WifiIssueComment/list', 'WifiIssueCommentController@list')->name('manager_wifi.WifiIssueComment.list'); // wifi报修评论列表
    Route::any('WifiIssueComment/add', 'WifiIssueCommentController@detail')->name('manager_wifi.WifiIssueComment.detail'); // wifi报修评论详情


});