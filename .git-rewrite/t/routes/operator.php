<?php
Route::prefix('operator')->group(function () {
    // 学校管理
    Route::get('schools/enter', 'SchoolsController@enter')->name('operator.schools.enter');  // 进入指定学校
    Route::get('campus/view', 'CampusController@view')->name('operator.campus.view');   // 显示校园的包含的学院的信息
});