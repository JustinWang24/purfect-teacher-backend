<?php
Route::prefix('manager_welcome')->group(function ()
{
    // 迎新配置
    Route::get('/welcome-config/index','Welcome\WelcomeConfigController@index')
        ->name('welcome_manager.welcomeConfig.index'); // 迎新配置
    Route::post('/welcome-config/save-base-info','Welcome\WelcomeConfigController@save_base_info')
        ->name('welcome_manager.welcomeConfig.save_base_info'); // 迎新配置保存
    Route::post('/welcome-config/save-user-info','Welcome\WelcomeConfigController@save_user_info')
        ->name('welcome_manager.welcomeConfig.save_user_info');// 个人信息保存
    Route::post('/welcome-config/save-report-confirm-info','Welcome\WelcomeConfigController@save_report_confirm_info')
        ->name('welcome_manager.welcomeConfig.save_report_confirm_info');// 报到确认
    Route::post('/welcome-config/save-report-bill-info','Welcome\WelcomeConfigController@save_report_bill_info')
        ->name('welcome_manager.welcomeConfig.save_report_bill_info');// 报到单
    Route::any('/welcome-config/get-report-list-info','Welcome\WelcomeConfigController@get_report_list_info')
        ->name('welcome_manager.welcomeConfig.get_report_list_info'); // 获取迎新流程
    Route::any('/welcome-config/delete-report-info','Welcome\WelcomeConfigController@delete_report_info')
        ->name('welcome_manager.welcomeConfig.delete_report_info'); // 删除单个流程
    Route::any('/welcome-config/up-report-info','Welcome\WelcomeConfigController@up_report_info')
        ->name('welcome_manager.welcomeConfig.up_report_info'); // 流程向上
    Route::any('/welcome-config/down-report-info','Welcome\WelcomeConfigController@down_report_info')
        ->name('welcome_manager.welcomeConfig.down_report_info'); // 流程向下

    // 迎新管理
    Route::any('/welcome-config/wait-list','Welcome\WelcomeReportController@wait_list')
        ->name('welcome_manager.welcomeReport.wait_list'); // 待报到列表
    Route::any('/welcome-config/processing-list','Welcome\WelcomeReportController@processing_list')
        ->name('welcome_manager.welcomeReport.processing_list'); // 报到中
    Route::any('/welcome-config/completed-list','Welcome\WelcomeReportController@completed_list')
        ->name('welcome_manager.welcomeReport.completed_list'); // 已完成
    Route::any('/welcome-config/detail','Welcome\WelcomeReportController@detail')
        ->name('welcome_manager.welcomeReport.detail'); // 详情

    // 费用管理
    Route::any('wifi/list', 'WifiController@list')->name('manager_wifi.wifi.list'); // wifi列表



});
