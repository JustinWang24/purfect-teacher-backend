<?php

# 该文件用于存放教师端路由

Route::prefix('teacher')->group(function () {

    Route::get('index', 'SchoolSceneryController@index')->name('teacher.scenery.index'); // 校园风采

    Route::get('profile', 'SchoolSceneryController@profile')->name('teacher.scenery.profile'); // 学校简介


    #会议
    Route::get('conference/index', 'ConferenceController@index')->name('teacher.conference.index'); //会议列表
    Route::get('conference/data', 'ConferenceController@data')->name('teacher.conference.data'); //数据接口
    Route::get('conference/add', 'ConferenceController@add')->name('teacher.conference.add'); //数据接口

});
