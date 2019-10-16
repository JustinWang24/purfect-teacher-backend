<?php

# 该文件用于存放教师端路由

Route::prefix('teacher')->group(function () {

    Route::get('index', 'SchoolSceneryController@index'); // 校园风采

    Route::get('profile', 'SchoolSceneryController@profile'); // 学校简介

});
