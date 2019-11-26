<?php

Route::prefix('verified_student')->group(function () {
    Route::get('profile/edit', 'StudentsController@edit')
        ->name('verified_student.profile.edit'); // 显示编辑学生资料的界面
    Route::post('profile/update', 'StudentsController@update')
        ->name('verified_student.profile.update'); // 显示编辑学生资料的界面

    // 校园通讯录
    Route::get('contacts/list', 'StudentsController@contacts_list')
        ->name('verified_student.contacts.list');
});