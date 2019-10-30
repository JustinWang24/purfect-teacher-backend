<?php

# 该文件用于存放教师端路由

Route::prefix('teacher')->group(function () {

    Route::get('index', 'SchoolSceneryController@index')->name('teacher.scenery.index'); // 校园风采

    Route::get('profile', 'SchoolSceneryController@profile')->name('teacher.scenery.profile'); // 学校简介


    #会议
    Route::get('conference/index', 'ConferenceController@index')->name('teacher.conference.index');      //会议列表
    Route::get('conference/data', 'ConferenceController@data')->name('teacher.conference.data');         //数据接口
    Route::get('conference/add', 'ConferenceController@add')->name('teacher.conference.add');            //添加页面
    Route::post('conference/create', 'ConferenceController@create')->name('teacher.conference.create');   //添加接口
    Route::get('conference/getUsers', 'ConferenceController@getUsers')->name('teacher.conference.getUsers'); //获取参会人员接口
    Route::get('conference/getRooms', 'ConferenceController@getRooms')->name('teacher.conference.getRooms'); //获取会议室




    //考试管理
    Route::get('exam/index', 'ExamController@index')->name('teacher.exam.index');                        // 列表
    Route::get('exam/create', 'ExamController@create')->name('teacher.exam.create');                     // 创建考试
    Route::get('exam/data', 'ExamController@data')->name('teacher.exam.data');                           // 创建考试
    Route::get('exam/getClassRooms', 'ExamController@getClassRooms')->name('teacher.exam.getClassRooms');// 获取教室列表
    Route::get('exam/getCourses', 'ExamController@getCourses')->name('teacher.exam.getCourses');         // 获取课程列表

});
