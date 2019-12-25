<?php
use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 项目管理
Route::prefix('project')->middleware('auth:api')->group(function () {
    // 项目列表
    Route::any('/getOaProjectListInfo','Api\OA\ProjectsController@projectList')
        ->name('oa.project.getOaProjectListInfo');

    // 项目详情
    Route::any('/getOaProjectInfo','Api\OA\ProjectsController@projectInfo')
        ->name('oa.project.getOaProjectInfo');
    // 创建项目
    Route::any('/addOaProjectInfo','Api\OA\ProjectsController@createProject')
        ->name('oa.project.addOaProjectInfo');
    // 人员列表-项目
    Route::any('/getOaProjectUserListInfo','Api\OA\ProjectsController@getOaTaskUserListInfo')
        ->name('oa.project.getOaProjectUserListInfo');

});


Route::prefix('task')->middleware('auth:api')->group(function () {
    // 项目下的任务列表
    Route::any('/getOaTaskListInfo','Api\OA\ProjectsController@taskList')
        ->name('oa.task.getOaTaskListInfo');
    Route::any('/addOaTaskInfo','Api\OA\ProjectsController@createTask')
        ->name('oa.task.addOaTaskInfo');
    Route::any('/getOaTaskInfo','Api\OA\ProjectsController@taskInfo')
        ->name('oa.task.getOaTaskInfo');
    Route::any('/finishOaTaskInfo','Api\OA\ProjectsController@finishTask')
        ->name('oa.task.finishOaTaskInfo');
    Route::any('/addOaTaskForum','Api\OA\ProjectsController@addOaTaskForum')
        ->name('oa.task.addOaTaskForum');
    Route::any('/getOaTaskUserListInfo','Api\OA\ProjectsController@getOaTaskUserListInfo')
        ->name('oa.task.getOaTaskUserListInfo');

});
Route::prefix('attendance')->middleware('auth:api')->group(function () {

    Route::any('/postTodayInfo','Api\OA\AttendanceTeacherController@postTodayInfo')
        ->name('oa.attendance.postTodayInfo');
    Route::any('/getTodayInfo','Api\OA\AttendanceTeacherController@getTodayInfo')
        ->name('oa.attendance.getTodayInfo');
    Route::any('/getMissList','Api\OA\AttendanceTeacherController@getMissList')
        ->name('oa.attendance.getMissList');
    Route::any('/getJsMonthCount','Api\OA\AttendanceTeacherController@getJsMonthCount')
        ->name('oa.attendance.getJsMonthCount');
    Route::any('/mac_add','Api\OA\AttendanceTeacherController@mac_add')
        ->name('oa.attendance.mac_add');
    Route::any('/apply_add','Api\OA\AttendanceTeacherController@apply_add')
        ->name('oa.attendance.apply_add');
    Route::any('/apply_list','Api\OA\AttendanceTeacherController@apply_list')
        ->name('oa.attendance.apply_list');

});
