<?php

Route::prefix('school_manager')->group(function () {
    // 学校管理
    Route::get('school/view', 'CampusController@school')->name('school_manager.school.view'); // 显示学校的统计信息

    Route::get('campus/add', 'CampusController@add')->name('school_manager.campus.add');    // 添加校区
    Route::get('campus/edit', 'CampusController@edit')->name('school_manager.campus.edit'); // 编辑校区
    Route::post('campus/update', 'CampusController@update')->name('school_manager.campus.update'); // 保存校区
    Route::get('campus/institutes', 'CampusController@institutes')->name('school_manager.campus.institutes'); // 显示校园的包含的学院的信息
    Route::get('campus/users', 'UsersController@users')->name('school_manager.campus.users'); // 显示学校的统计信息

    // 学生管理: 只有 学校管理员以上级别的角色才可以添加,编辑,学生
    Route::get('student/add', 'StudentsController@add')->name('school_manager.student.add');    // 添加学生
    Route::get('student/edit', 'StudentsController@edit')->name('school_manager.student.edit'); // 编辑学生
    Route::get('student/suspend', 'StudentsController@suspend')->name('school_manager.student.suspend'); // 编辑学生
    Route::get('student/stop', 'StudentsController@stop')->name('school_manager.student.stop'); // 编辑学生
    Route::get('student/reject', 'StudentsController@reject')->name('school_manager.student.reject'); // 编辑学生
    Route::post('student/update', 'StudentsController@update')->name('school_manager.student.update'); // 编辑学生
});