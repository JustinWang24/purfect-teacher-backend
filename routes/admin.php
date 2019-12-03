<?php
// 管理后台路由: 超级管理员专有的路由, 这里定义的路由, 其他任何角色都不能访问到
Route::prefix('admin')->group(function () {
    // 学校管理
    Route::get('schools/statistic', 'SchoolsController@statistic')->name('admin.schools.statistic'); // 显示学校的统计信息
    Route::get('schools/add', 'SchoolsController@add')->name('admin.schools.add');    // 显示创建学校的界面
    Route::get('schools/edit', 'SchoolsController@edit')->name('admin.schools.edit');    // 显示学校信息编辑的界面
    Route::post('schools/update', 'SchoolsController@update')->name('admin.schools.update');  // 进入指定学校

    // 角色与权限管理路由组
    Route::get('roles/list', 'RolesController@index')->name('admin.roles.list');
    Route::get('roles/edit', 'RolesController@edit')->name('admin.roles.edit');
    Route::post('roles/update_permission', 'RolesController@update_permission')->name('admin.roles.update_permission');

    //版本管理
    Route::get('versions/list', 'VersionController@index')->name('admin.versions.list');
    Route::get('versions/add', 'VersionController@add')->name('admin.versions.add');
    Route::get('versions/edit', 'VersionController@edit')->name('admin.versions.edit');
    Route::get('versions/delete', 'VersionController@delete')->name('admin.versions.delete');
    Route::post('versions/update', 'VersionController@update')->name('admin.versions.update');

    // 创建学校管理员
    Route::any('create/school-manager', 'SchoolsController@create_school_manager')
        ->name('admin.create.school-manager');
    // 创建学校管理员
    Route::get('edit/school-manager', 'SchoolsController@edit_school_manager')
        ->name('admin.edit.school-manager');

    Route::get('importer/manager', 'ImporterController@manager')->name('admin.importer.manager');
    Route::post('importer/update', 'ImporterController@update')->name('admin.importer.update');
    Route::get('importer/add', 'ImporterController@add')->name('admin.importer.add');
    Route::get('importer/edit', 'ImporterController@edit')->name('admin.importer.edit');
    Route::get('importer/handle/{id}', 'ImporterController@handle')->name('admin.importer.handle');
});
