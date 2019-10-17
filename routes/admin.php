<?php
// 管理后台路由
Route::prefix('admin')->group(function () {
    Route::get('index', 'IndexController@index');
    // 学校管理
    Route::get('schools/statistic', 'SchoolsController@statistic')->name('admin.schools.statistic'); // 显示学校的统计信息
    Route::get('schools/edit', 'SchoolsController@index')->name('admin.schools.edit');    // 显示学校信息编辑的界面
    Route::get('schools/enter', 'SchoolsController@index')->name('admin.schools.enter');  // 进入指定学校

    // 角色与权限管理路由组
    Route::get('roles/list', 'RolesController@index')->name('admin.roles.list');
    Route::get('roles/edit', 'RolesController@edit')->name('admin.roles.edit');
    Route::post('roles/update_permission', 'RolesController@update_permission')->name('admin.roles.update_permission');
});
