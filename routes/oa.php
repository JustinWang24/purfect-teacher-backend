<?php
use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 项目管理
Route::prefix('project')->middleware('auth:api')->group(function () {
    // 项目列表
    Route::post('/getOaProjectListInfo/page/{page}','Api\OA\ProjectsController@projectList')
        ->name('oa.project.getOaProjectListInfo');

    // 项目详情
    Route::post('/getOaProjectInfo/projectid/{project_id}','Api\OA\ProjectsController@projectInfo')
        ->name('oa.project.getOaProjectInfo');
    // 创建项目
    Route::post('/addOaProjectInfo','Api\OA\ProjectsController@createProject')
        ->name('oa.project.addOaProjectInfo');
    // 人员列表-项目
    Route::post('/getOaProjectUserListInfo','Api\OA\ProjectsController@getOaTaskUserListInfo')
        ->name('oa.project.getOaProjectUserListInfo');
//    // 创建任务
//    Route::post('/create-task','Api\OA\ProjectsController@createTask')
//        ->name('api.oa.create-task');
//    // 创建任务讨论
//    Route::post('/create-discussion','Api\OA\ProjectsController@createDiscussion')
//        ->name('api.oa.create-discussion');
//    // 任务下的评论列表
//    Route::get('/discussion-list','Api\OA\ProjectsController@discussionList')
//        ->name('api.oa.discussion-list');
//    // 项目详情修改
//    Route::post('/update-project','Api\OA\ProjectsController@updateProject')
//        ->name('api.oa.update-project');
});


Route::prefix('task')->middleware('auth:api')->group(function () {
    // 项目下的任务列表
    Route::post('/getOaTaskListInfo/page/{page}/type/{type}','Api\OA\ProjectsController@taskList')
        ->name('oa.task.getOaTaskListInfo');
    Route::post('/addOaTaskInfo','Api\OA\ProjectsController@createTask')
        ->name('oa.task.addOaTaskInfo');
    Route::post('/getOaTaskInfo/taskid/{taskid}','Api\OA\ProjectsController@taskInfo')
        ->name('oa.task.getOaTaskInfo');
    Route::any('/finishOaTaskInfo','Api\OA\ProjectsController@finishTask')
        ->name('oa.task.finishOaTaskInfo');
    Route::post('/addOaTaskForum','Api\OA\ProjectsController@addOaTaskForum')
        ->name('oa.task.addOaTaskForum');
    Route::post('/getOaTaskUserListInfo','Api\OA\ProjectsController@getOaTaskUserListInfo')
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

});
