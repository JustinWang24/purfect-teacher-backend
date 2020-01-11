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
    // 创建任务
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

    Route::any('/postTodayInfo','Api\OA\OaAttendanceTeacherController@postTodayInfo')
        ->name('oa.attendance.postTodayInfo');
    Route::any('/getTodayInfo','Api\OA\OaAttendanceTeacherController@getTodayInfo')
        ->name('oa.attendance.getTodayInfo');
    Route::any('/getMissList','Api\OA\OaAttendanceTeacherController@getMissList')
        ->name('oa.attendance.getMissList');
    Route::any('/getJsMonthCount','Api\OA\OaAttendanceTeacherController@getJsMonthCount')
        ->name('oa.attendance.getJsMonthCount');
    Route::any('/mac_add','Api\OA\OaAttendanceTeacherController@mac_add')
        ->name('oa.attendance.mac_add');
    Route::any('/apply_add','Api\OA\OaAttendanceTeacherController@apply_add')
        ->name('oa.attendance.apply_add');
    Route::any('/apply_list','Api\OA\OaAttendanceTeacherController@apply_list')
        ->name('oa.attendance.apply_list');
    Route::any('/get_relationship','Api\OA\OaAttendanceTeacherController@get_relationship')
        ->name('oa.attendance.get_relationship');
    Route::any('/getGroupList','Api\OA\OaAttendanceTeacherController@getGroupList')
        ->name('oa.attendance.getGroupList');
    Route::any('/getMonthManage','Api\OA\OaAttendanceTeacherController@getMonthManage')
        ->name('oa.attendance.getMonthManage');
    Route::any('/getTodayManage','Api\OA\OaAttendanceTeacherController@getTodayManage')
        ->name('oa.attendance.getTodayManage');

});

Route::prefix('Leave')->middleware('auth:api')->group(function () {
    Route::any('/get_relationship','Api\OA\OaAttendanceTeacherController@get_relationship')
        ->name('oa.Leave.get_relationship');
    Route::any('/get_category','Api\OA\OaAttendanceTeacherController@get_leave_category')
        ->name('oa.Leave.get_category');
    Route::any('/add','Api\OA\OaAttendanceTeacherController@takeLeave')
        ->name('oa.Leave.add');
});
Route::prefix('Away')->middleware('auth:api')->group(function () {
    Route::any('/get_relationship','Api\OA\OaAttendanceTeacherController@get_relationship')
        ->name('oa.Away.get_relationship');
    Route::any('/add','Api\OA\OaAttendanceTeacherController@addVisit')
        ->name('oa.Away.add');
    Route::any('/get_category','Api\OA\OaAttendanceTeacherController@get_visit_category')
        ->name('oa.Away.get_category');
});
Route::prefix('Approves')->middleware('auth:api')->group(function () {
    Route::any('/my_list','Api\OA\OaAttendanceTeacherController@my_list')
        ->name('oa.Approves.my_list');
    Route::any('/wait_list','Api\OA\OaAttendanceTeacherController@wait_list')
        ->name('oa.Approves.wait_list');
    Route::any('/info','Api\OA\OaAttendanceTeacherController@info')
        ->name('oa.Approves.info');
});


// 会议
Route::prefix('meeting')->middleware('auth:api')->group(function () {
    // 创建分组
    Route::post('/addGroup','Api\OA\GroupController@addGroup')
        ->name('Oa.meeting.addGroup');
    // 分组列表
    Route::post('/groupList','Api\OA\GroupController@groupList')
        ->name('Oa.meeting.groupList');
    // 教师列表
    Route::post('/userList','Api\OA\GroupController@userList')
        ->name('Oa.meeting.userList');
    // 添加成员
    Route::post('/addMember','Api\OA\GroupUserController@addMember')
        ->name('Oa.meeting.addMember');
    // 删除分组
    Route::post('/delGroup','Api\OA\GroupController@delGroup')
        ->name('Oa.meeting.delGroup');
    // 删除成员
    Route::post('/delMember','Api\OA\GroupController@delMember')
        ->name('Oa.meeting.delMember');
    // 创建会议
    Route::post('/addMeeting','Api\OA\MeetIngController@addMeeting')
        ->name('Oa.meeting.addMeeting');
    // 待签列表
    Route::post('/todoList','Api\OA\MeetIngController@todoList')
        ->name('Oa.meeting.todoList');
    // 签到签退
    Route::post('/qrcode','Api\OA\MeetIngController@qrcode')
        ->name('Oa.meeting.qrcode');
    // 已完成列表
    Route::post('/doneList','Api\OA\MeetIngController@doneList')
        ->name('Oa.meeting.doneList');
    // 创建的列表
    Route::post('/myList','Api\OA\MeetIngController@myList')
        ->name('Oa.meeting.myList');
    // 会议详情-参与者
    Route::post('/uinfo','Api\OA\MeetIngController@meetingMember')
        ->name('Oa.meeting.uinfo');
    // 会议详情-创建者
    Route::post('/minfo','Api\OA\MeetIngController@minfo')
        ->name('Oa.meeting.minfo');
    // 签到记录
    Route::post('/signLog','Api\OA\MeetIngController@signLog')
        ->name('Oa.meeting.signLog');
});
