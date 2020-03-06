<?php
use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 组织架构
Route::prefix('tissue')->middleware('auth:api')->group(function () {
    // 获取组织和人员
    Route::post('/getOrganization','Api\School\OrganizationController@getOrganization')
        ->name('oa.tissue.getOrganization');
});

// 项目管理
Route::prefix('project')->middleware('auth:api')->group(function () {
    // 创建项目
    Route::post('/addOaProjectInfo','Api\OA\ProjectsController@createProject')
        ->name('Oa.project.addOaProjectInfo');
    // 项目列表
    Route::post('/getOaProjectListInfo','Api\OA\ProjectsController@projectList')
        ->name('Oa.project.getOaProjectListInfo');
    // 项目详情
    Route::post('/getOaProjectInfo','Api\OA\ProjectsController@projectInfo')
        ->name('Oa.project.getOaProjectInfo');

    // 人员列表-项目
    Route::any('/getOaProjectUserListInfo','Api\OA\ProjectsController@getOaTaskUserListInfo')
        ->name('oa.project.getOaProjectUserListInfo');

});

// 任务管理
Route::prefix('task')->middleware('auth:api')->group(function () {
    // 项目下的任务列表
    Route::post('/getOaTaskListInfo','Api\OA\TaskController@taskList')
        ->name('Oa.task.getOaTaskListInfo');
    // 创建任务
    Route::post('/addOaTaskInfo','Api\OA\TaskController@createTask')
        ->name('Oa.task.addOaTaskInfo');
    // 任务详情
    Route::post('/getOaTaskInfo','Api\OA\TaskController@taskInfo')
        ->name('Oa.task.getOaTaskInfo');
    // 接收任务
    Route::post('/receiveOaTaskInfo','Api\OA\TaskController@receiveTask')
        ->name('Oa.task.receiveOaTaskInfo');
    // 完成任务
    Route::post('/finishOaTaskInfo','Api\OA\TaskController@finishTask')
        ->name('Oa.task.finishOaTaskInfo');
    // 发起讨论
    Route::post('/addOaTaskForum','Api\OA\TaskController@addTaskForum')
        ->name('Oa.task.addOaTaskForum');
    // 删除讨论
    Route::post('/delOaTaskForum','Api\OA\TaskController@delTaskForum')
        ->name('Oa.task.delOaTaskForum');
    // 结果列表
    Route::post('/getOaTaskReport','Api\OA\TaskController@taskReport')
        ->name('Oa.task.getOaTaskReport');
    // 未读数
    Route::any('/taskStatus','Api\OA\TaskController@taskStatus')
        ->name('Oa.task.taskStatus');
    // 指派任务
    Route::post('/addOaTaskUser','Api\OA\TaskController@addTaskUser')
        ->name('Oa.task.addOaTaskUser');

    Route::any('/getOaTaskUserListInfo','Api\OA\ProjectsController@getOaTaskUserListInfo')
        ->name('Oa.task.getOaTaskUserListInfo');

});


Route::prefix('attendance')->middleware('auth:api')->group(function () {

    Route::post('/getTodayInfo','Api\TeacherAttendance\AttendanceController@getTodayInfo')
        ->name('oa.attendance.getTodayInfo');
    Route::post('/postTodayInfo','Api\TeacherAttendance\AttendanceController@postTodayInfo')
        ->name('oa.attendance.postTodayInfo');
    Route::post('/mac_add','Api\TeacherAttendance\AttendanceController@macAdd')
        ->name('oa.attendance.mac_add');
    Route::post('/getMonthCount','Api\TeacherAttendance\AttendanceController@getMonthCount')
        ->name('oa.attendance.getMonthCount');
    Route::post('/getGroupList','Api\TeacherAttendance\AttendanceController@getGroupList')
        ->name('oa.attendance.getGroupList');
    Route::post('/getManageDayCount','Api\TeacherAttendance\AttendanceController@getManageDayCount')
        ->name('oa.attendance.getManageDayCount');

});
Route::prefix('electivecourse')->middleware('auth:api')->group(function () {
    Route::post('/apply', 'Api\ElectiveCourse\OaElectiveCourseController@apply')
        ->name('oa.electivecourse.apply');
    Route::post('/conf', 'Api\ElectiveCourse\OaElectiveCourseController@conf')
        ->name('oa.electivecourse.conf');
    Route::post('lists', 'Api\ElectiveCourse\OaElectiveCourseController@lists')
        ->name('oa.electivecourse.lists');
    Route::post('info', 'Api\ElectiveCourse\OaElectiveCourseController@info')
        ->name('oa.electivecourse.info');
    Route::post('applylists', 'Api\ElectiveCourse\OaElectiveCourseController@applylists')
        ->name('oa.electivecourse.applylists');
    Route::post('applyinfo', 'Api\ElectiveCourse\OaElectiveCourseController@applyinfo')
        ->name('oa.electivecourse.applyinfo');
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

// 到访
Route::prefix('visitor')->middleware('auth:api')->group(function () {
    // 到访列表
    Route::post('/list','Api\OA\VisitorController@list')
        ->name('Oa.visitor.list');
    // 到访详情
    Route::post('/detail','Api\OA\VisitorController@detail')
        ->name('Oa.visitor.detail');
    // 获取分享信息
    Route::post('/get-share-info','Api\OA\VisitorController@get_share_info')
        ->name('Oa.visitor.get_share_info');
});

Route::any('/visitor-h5/info','Api\OA\VisitorController@info')->name('Oa.visitor.info'); // 被访信息
Route::any('/visitor-h5/get-visiter-info','Api\OA\VisitorController@get_visiter_info')->name('Oa.visitor.get_visiter_info'); // 获取访客信息
Route::post('/visitor-h5/update','Api\OA\VisitorController@update')->name('Oa.visitor.update'); // 到访提交
