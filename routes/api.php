<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 加载学校的作息时间表
Route::any('/school/load-time-slots','Api\School\TimeSlotsController@load_by_school')->name('api.school.load.time.slots');
// 获取某个学校所有的专业
Route::any('/school/load-majors','Api\School\MajorsController@load_by_school')->name('api.school.load.majors');
// 获取某个学校所有的专业
Route::any('/school/load-courses','Api\School\CoursesController@load_courses')->name('api.school.load.courses');
// 搜索某个学校的老师
Route::any('/school/search-teachers','Api\School\TeachersController@search_by_name')->name('api.school.search.teachers');
// 搜索某个学校的老师
Route::any('/school/save-course','Api\School\CoursesController@save_course')->name('api.school.save.course');
Route::any('/school/delete-course','Api\School\CoursesController@delete_course')->name('api.school.delete.course');