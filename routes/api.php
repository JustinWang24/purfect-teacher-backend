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
Route::any('/school/load-study-time-slots','Api\School\TimeSlotsController@load_study_time_slots')->name('api.school.load.study.time.slots');
// 获取某个学校所有的专业
Route::any('/school/load-majors','Api\School\MajorsController@load_by_school')->name('api.school.load.majors');
// 获取某个专业的所有班级
Route::any('/school/load-major-grades','Api\School\MajorsController@load_major_grades')->name('api.school.load.major.grades');
// 获取某个专业的所有课程
Route::any('/school/load-major-courses','Api\School\MajorsController@load_major_courses')->name('api.school.load.major.courses');
// 获取某个学校所有的专业
Route::any('/school/load-courses','Api\School\CoursesController@load_courses')->name('api.school.load.courses');
// 搜索某个学校的老师
Route::any('/school/search-teachers','Api\School\TeachersController@search_by_name')->name('api.school.search.teachers');
Route::any('/school/load-course-teachers','Api\School\TeachersController@load_course_teachers')->name('api.school.load.course.teachers');
// 搜索某个学校的老师
Route::any('/school/save-course','Api\School\CoursesController@save_course')->name('api.school.save.course');
Route::any('/school/delete-course','Api\School\CoursesController@delete_course')->name('api.school.delete.course');
// 获取学校的所有建筑
Route::any('/school/load-buildings','Api\School\LocationController@load_buildings')->name('api.school.load.buildings');
Route::any('/school/load-building-rooms','Api\School\LocationController@load_building_rooms')->name('api.school.load.building.rooms');
Route::any('/school/load-building-available-rooms','Api\School\LocationController@load_building_available_rooms')->name('api.school.load.building.available.rooms');