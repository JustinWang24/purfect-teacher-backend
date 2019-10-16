<?php

// 管理后台路由
Route::prefix('admin')->group(function () {
    Route::get('index', 'IndexController@index');

    // 角色与权限管理路由组
    Route::get('roles', 'RolesController@index')->name('admin.roles.list');
});
