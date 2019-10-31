<?php

# 该文件用于存放教师端路由

Route::prefix('teacher')->group(function () {

    Route::get('index', 'SchoolSceneryController@index')->name('teacher.scenery.index');       // 校园风采
    Route::get('profile', 'SchoolSceneryController@profile')->name('teacher.scenery.profile'); // 学校简介

    Route::get('conference/index', 'ConferenceController@index')->name('teacher.conference.index');          // 会议列表
    Route::get('conference/data', 'ConferenceController@data')->name('teacher.conference.data');             // 数据接口
    Route::get('conference/add', 'ConferenceController@add')->name('teacher.conference.add');                // 添加页面
    Route::post('conference/create', 'ConferenceController@create')->name('teacher.conference.create');      // 添加接口
    Route::get('conference/getUsers', 'ConferenceController@getUsers')->name('teacher.conference.getUsers'); // 获取参会人员接口
    Route::get('conference/getRooms', 'ConferenceController@getRooms')->name('teacher.conference.getRooms'); // 获取会议室

    Route::get('preset/step', 'OfficialDocumentController@presetStep')->name('teacher.get.preset.step');                    // 所有系统预置步骤
    Route::post('production/process', 'OfficialDocumentController@productionProcess')->name('teacher.production.process');  // 生成公文流程
    Route::get('official/document', 'OfficialDocumentController@getProcess')->name('teacher.get.official.document');        // 学校公文列表
    Route::get('one/process', 'OfficialDocumentController@getProcessDetails')->name('teacher.get.one.process');             // 获取公文流程详情
    Route::post('add/step_user', 'OfficialDocumentController@addStepUser')->name('teacher.add.step.user');                  // 添加流程中的步骤负责人
    Route::post('update/step_user', 'OfficialDocumentController@updateStepUser')->name('teacher.update.step.user');         // 修改流程中的步骤负责人

});
