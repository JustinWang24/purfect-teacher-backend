<?php
Route::prefix('operator')->group(function () {
    // 学校管理
    Route::get('school/view', 'CampusController@school')->name('operator.school.view'); // 显示学校的统计信息
    Route::get('campus/view', 'CampusController@view')->name('operator.campus.view'); // 显示学校的统计信息
    Route::get('campus/add', 'CampusController@add')->name('operator.campus.add'); // 显示学校的统计信息
    Route::get('campus/edit', 'CampusController@edit')->name('operator.campus.edit'); // 显示学校的统计信息
    Route::get('campus/institutes', 'CampusController@edit')->name('operator.campus.institutes'); // 显示学校的统计信息
    Route::get('campus/users', 'CampusController@edit')->name('operator.campus.users'); // 显示学校的统计信息
    Route::post('campus/update', 'CampusController@update')->name('operator.campus.update'); // 显示学校的统计信息
});