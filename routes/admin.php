<?php
// 管理后台路由
Route::prefix('admin')->group(function () {
    Route::get('index', 'IndexController@index');
    // 角色与权限管理路由组
    Route::get('roles/list', 'RolesController@index')->name('admin.roles.list');
    Route::get('roles/edit', 'RolesController@edit')->name('admin.roles.edit');
});
