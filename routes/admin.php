<?php

// 管理后台路由
Route::prefix('admin')->group(function () {

    Route::get('index', 'IndexController@index'); //


});
