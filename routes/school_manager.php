<?php

Route::prefix('school_manager')->group(function () {
    // 学校管理
    Route::get('school/view', 'CampusController@school')->name('school_manager.school.view'); // 显示学校的统计信息
    Route::get('school/institutes', 'SchoolsController@institutes')->name('school_manager.school.institutes'); // 显示学校的所有学院
    Route::get('school/departments', 'SchoolsController@departments')->name('school_manager.school.departments'); // 显示学校的所有系
    Route::get('school/majors', 'SchoolsController@majors')->name('school_manager.school.majors'); // 显示学校的所有专业
    Route::get('school/years', 'SchoolsController@years')->name('school_manager.school.years'); // 显示学校的所有年级
    Route::get('school/grades', 'SchoolsController@grades')->name('school_manager.school.grades'); // 显示学校的所有班级
    Route::get('school/teachers', 'SchoolsController@teachers')->name('school_manager.school.teachers'); // 显示学校的所有老师
    Route::get('school/students', 'SchoolsController@students')->name('school_manager.school.students'); // 显示学校的所有学生
    Route::get('school/rooms', 'SchoolsController@rooms')->name('school_manager.school.rooms'); // 显示学校的所有学生
    Route::get('school/organization-manager', 'SchoolsController@organization')
        ->name('school_manager.school.organization-manager'); // 显示学校的组织架构
    Route::post('organizations/load-parent', 'SchoolsController@load_parent')
        ->name('school_manager.organizations.load-parent'); // 加载某一级别机构的上级

    Route::post('organizations/load-children', 'SchoolsController@load_children')
        ->name('school_manager.organizations.load-children'); // 加载某一级别机构的下级
    Route::post('organizations/load-by-orgs', 'SchoolsController@load_by_orgs')
        ->name('school_manager.organizations.load-by-orgs'); // 加载联动的数据
    Route::post('organizations/load-all', 'SchoolsController@load_all')
        ->name('school_manager.organizations.load-all'); // 加载联动的全部数据

    Route::post('organizations/save', 'SchoolsController@save_organization')
        ->name('school_manager.organizations.save'); // 保存学校的组织架构
    Route::post('organizations/load', 'SchoolsController@load_organization')
        ->name('school_manager.organizations.load'); // 加载 单个 学校的组织架构
    Route::post('organizations/delete', 'SchoolsController@delete_organization')
        ->name('school_manager.organizations.delete'); // 加载 单个 学校的组织架构
    Route::post('organizations/add-member', 'SchoolsController@add_member')
        ->name('school_manager.organizations.add-member'); // 给机构添加成员
    Route::post('organizations/remove-member', 'SchoolsController@remove_member')
        ->name('school_manager.organizations.remove-member'); // 删除

    Route::get('organizations/teaching-and-research-group', 'SchoolsController@teaching_and_research_group')
        ->name('school_manager.organizations.teaching-and-research-group'); // 加载教研组
    Route::get('organizations/teaching-and-research-group/members', 'SchoolsController@teaching_and_research_group_members')
        ->name('school_manager.organizations.teaching-and-research-group-members'); // 添加教研组
    Route::get('organizations/teaching-and-research-group/add', 'SchoolsController@teaching_and_research_group_add')
        ->name('school_manager.organizations.teaching-and-research-group-add'); // 添加教研组
    Route::get('organizations/teaching-and-research-group/edit', 'SchoolsController@teaching_and_research_group_edit')
        ->name('school_manager.organizations.teaching-and-research-group-edit'); // 修改教研组
    Route::get('organizations/teaching-and-research-group/delete', 'SchoolsController@teaching_and_research_group_delete')
        ->name('school_manager.organizations.teaching-and-research-group-delete'); // 删除教研组
    Route::post('organizations/teaching-and-research-group/delete-member', 'SchoolsController@teaching_and_research_group_delete_member')
        ->name('school_manager.organizations.teaching-and-research-group-delete-member'); // 删除教研组
    Route::post('organizations/teaching-and-research-group/save', 'SchoolsController@teaching_and_research_group_save')
        ->name('school_manager.organizations.teaching-and-research-group-save'); // 保存教研组
    Route::post('organizations/teaching-and-research-group/save-members', 'SchoolsController@teaching_and_research_group_save_members')
        ->name('school_manager.organizations.teaching-and-research-group-save-members'); // 保存教研组

    Route::any('school/set-year-manager', 'SchoolsController@set_year_manager')->name('school_manager.school.set-year-manager'); // 显示学校的所有年级

    // 校区的管理
    Route::get('campus/add', 'CampusController@add')->name('school_manager.campus.add');                        // 添加校区
    Route::get('campus/edit', 'CampusController@edit')->name('school_manager.campus.edit');                     // 编辑校区
    Route::post('campus/update', 'CampusController@update')->name('school_manager.campus.update');              // 保存校区
    Route::get('campus/users', 'UsersController@users')->name('school_manager.campus.users');                   // 显示校区的学生/教师列表

    Route::get('campus/buildings', 'CampusController@buildings')->name('school_manager.campus.buildings');   // 显示校区的包含的建筑信息
    Route::get('campus/institutes', 'CampusController@institutes')->name('school_manager.campus.institutes');   // 显示校区的包含的学院的信息

    // 学院的管理
    Route::get('institute/edit/id/id', 'InstitutesController@edit')->name('school_manager.institute.edit');           // 编辑学院
    Route::get('institute/add', 'InstitutesController@add')->name('school_manager.institute.add');              // 添加学院
    Route::post('institute/update', 'InstitutesController@update')->name('school_manager.institute.update');    // 保存学院
    Route::get('institute/users', 'UsersController@users')->name('school_manager.institute.users');        // 显示学院的学生/教师列表
    Route::get('institute/departments', 'InstitutesController@departments')->name('school_manager.institute.departments');  // 显示学院的所有系

    // 建筑物的管理
    Route::get('building/edit', 'BuildingsController@edit')->name('school_manager.building.edit');           // 编辑建筑物
    Route::get('building/add', 'BuildingsController@add')->name('school_manager.building.add');              // 添加建筑物
    Route::get('building/rooms', 'BuildingsController@rooms')->name('school_manager.building.rooms');        // 建筑物的房间
    Route::post('building/update', 'BuildingsController@update')->name('school_manager.building.update');    // 保存建筑物

    // 房间管理
    Route::get('room/edit', 'RoomsController@edit')->name('school_manager.room.edit');           // 编辑房间
    Route::get('room/add', 'RoomsController@add')->name('school_manager.room.add');              // 添加房间
    Route::get('room/delete', 'RoomsController@delete')->name('school_manager.room.delete');     // 删除房间
    Route::post('room/update', 'RoomsController@update')->name('school_manager.room.update');    // 保存房间

    // 系的管理
    Route::get('department/add', 'DepartmentsController@add')->name('school_manager.department.add');              // 添加系
    Route::get('department/edit', 'DepartmentsController@edit')->name('school_manager.department.edit');              // 编辑系
    Route::post('department/update', 'DepartmentsController@update')->name('school_manager.department.update');      // 保存系
    Route::get('department/majors', 'DepartmentsController@majors')->name('school_manager.department.majors');     // 系的专业列表
    Route::any('department/set-adviser', 'DepartmentsController@set_adviser')->name('school_manager.department.set.adviser');     // 设置系主任

    // 专业管理
    Route::get('major/add', 'MajorsController@add')->name('school_manager.major.add');           // 添加专业
    Route::get('major/edit', 'MajorsController@edit')->name('school_manager.major.edit');        // 编辑专业
    Route::post('major/update', 'MajorsController@update')->name('school_manager.major.update'); // 保存专业
    Route::get('major/grades', 'MajorsController@grades')->name('school_manager.major.grades');  // 专业的班级列表
    Route::get('major/users', 'MajorsController@users')->name('school_manager.major.users');     // 专业的学生列表

    // 班级管理
    Route::get('grade/add', 'GradesController@add')->name('school_manager.grade.add');           // 添加班级
    Route::get('grade/edit', 'GradesController@edit')->name('school_manager.grade.edit');        // 编辑班级
    Route::post('grade/update', 'GradesController@update')->name('school_manager.grade.update');        // 编辑班级

    Route::any('grade/set-adviser', 'GradesController@set_adviser')->name('school_manager.grade.set-adviser');     // 设置班主任

    // 学生管理: 只有 学校管理员以上级别的角色才可以添加,编辑,学生school_manager.scenery.edit
    Route::get('student/add', 'StudentsController@add')->name('school_manager.student.add');                // 添加学生
    Route::get('student/edit', 'StudentsController@edit')->name('school_manager.student.edit');             // 编辑学生
    Route::get('student/suspend', 'StudentsController@suspend')->name('school_manager.student.suspend');    // 休学
    Route::get('student/stop', 'StudentsController@stop')->name('school_manager.student.stop');             // 停课
    Route::get('student/reject', 'StudentsController@reject')->name('school_manager.student.reject');       // 退学
    Route::post('student/update', 'StudentsController@update')->name('school_manager.student.update');      // 保存学生
    Route::get('school/users', 'StudentsController@school_users')->name('school_manager.school.users');      // 已注册用户

    // 学校风采管理
    Route::get('scenery/list', 'SceneryController@list')->name('school_manager.scenery.list');      // 风采列表
    Route::get('scenery/add', 'SceneryController@add')->name('school_manager.scenery.add');         // 风采添加表单
    Route::get('scenery/edit', 'SceneryController@edit')->name('school_manager.scenery.edit');      // 风采修改表单
    Route::post('scenery/save', 'SceneryController@save')->name('school_manager.scenery.save');     // 风采保存

    // 课程表管理
    Route::get('timetable/manager', 'TimeTables\TimetablesController@manager')->name('school_manager.timetable.manager');
    // 添加课程表项目// 添加班级
    Route::get('timetable/manager/preview', 'TimeTables\TimetablesController@preview')->name('school_manager.timetable.manager.preview');           // 添加班级
    Route::get('timetable/manager/courses', 'TimeTables\CoursesController@manager')
        ->name('school_manager.courses.manager');

    // 从班级的, 或者是学生的角度, 查看课程表
    Route::get('timetable/manager/view-grade-timetable','TimeTables\TimetablesController@view_grade_timetable')
        ->name('school_manager.grade.view.timetable');
    // 从课程的角度, 查看课程表
    Route::get('timetable/manager/view-course-timetable','TimeTables\TimetablesController@view_course_timetable')
        ->name('school_manager.course.view.timetable');
    // 从授课老师的角度, 查看课程表
    Route::get('timetable/manager/view-teacher-timetable','TimeTables\TimetablesController@view_teacher_timetable')
        ->name('school_manager.teacher.view.timetable');
    // 从教室的角度, 查看课程表
    Route::get('timetable/manager/view-room-timetable','TimeTables\TimetablesController@view_room_timetable')
        ->name('school_manager.room.view.timetable');

    // 学校的基础配置信息
    Route::post('school/config/update','SchoolsController@config_update')
        ->name('school_manager.school.config.update');

    // 招生咨询管理
    Route::get('consult/list', 'RecruitStudent\ConsultController@list')->name('school_manager.consult.list');
    Route::any('consult/note', 'RecruitStudent\ConsultController@note')->name('school_manager.consult.note');
    Route::any('consult/add', 'RecruitStudent\ConsultController@add')->name('school_manager.consult.add');
    Route::any('consult/edit', 'RecruitStudent\ConsultController@edit')->name('school_manager.consult.edit');
    Route::get('consult/delete', 'RecruitStudent\ConsultController@delete')->name('school_manager.consult.delete');

    //设备管理
    Route::get('facility/list','FacilityController@list')->name('school_manager.facility.list');  // 设备列表
    Route::any('facility/add','FacilityController@add')->name('school_manager.facility.add');     // 添加设备
    Route::any('facility/edit','FacilityController@edit')->name('school_manager.facility.edit');  // 编辑设备
    Route::get('facility/delete','FacilityController@delete')->name('school_manager.facility.delete'); // 删除设备
    Route::get('facility/getBuildingList','FacilityController@getBuildingList')->name('school_manager.facility.getBuildingList'); // 获取建筑列表
    Route::get('facility/getRoomList','FacilityController@getRoomList')->name('school_manager.facility.getRoomList');  // 获取教室列表

    // 招生报名管理
    Route::get('registration/list', 'RecruitStudent\RegistrationInformatics@index')->name('school_manager.registration.list');  // 报名列表
    Route::get('registration/details', 'RecruitStudent\RegistrationInformatics@details')->name('school_manager.registration.details');  // 报名详情
    Route::get('registration/examine', 'RecruitStudent\RegistrationInformatics@examine')->name('school_manager.registration.examine');  // 报名审核

    // 迎新助手管理界面
    Route::get('welcome/manager', 'EnrolmentStepController@manager')
        ->name('school_manager.welcome.manager');

    // 报名表管理
//    Route::get('registration/examine', 'RecruitStudent\RegistrationInformatics@examine')->name('school_manager.registration.examine');  // 报名审核

    // 教材管理
    Route::get('textbook/loadCampusTextbook', 'TextbookController@loadCampusTextbook')
        ->name('school_manager.textbook.loadCampusTextbook'); // 获取校区教材采购情况
    Route::get('textbook/campusTextbookDownload', 'TextbookController@campusTextbookDownload')
        ->name('school_manager.textbook.campusTextbookDownload'); // 校区教材下载
    // 班级学生列表
    Route::get('textbook-grade', 'TextbookController@grade')
        ->name('school_manager.textbook.grade');
    // 学生教材列表
    Route::get('textbook-users', 'TextbookController@users')
        ->name('school_manager.textbook.users');

    // 当个领取教材
    Route::get('textbook-get', 'TextbookController@getTextbook')
        ->name('school_manager.textbook.get');
    // 批量领取
    Route::post('textbook-submit', 'TextbookController@submit')
        ->name('school_manager.textbook.submit');


    // 校历事件添加
    Route::any('calendar/save', 'Calendar\IndexController@save')->name('school_manger.school.calendar.save');
    // 校历事件的删除
    Route::any('calendar/delete', 'Calendar\IndexController@delete')->name('school_manger.school.calendar.delete');

    // 校历展示
    Route::any('calendar/index', 'Calendar\IndexController@index')->name('school_manger.school.calendar.index');

    // 获取校历事件详情
    Route::any('calendar/event/details', 'Calendar\IndexController@getEventDetails')->name('school_manger.calendar.event.details');

    //选课申请管理
    Route::post('elective-course/save','ApplyElectiveCourseController@create')
        ->name('school_manager.elective-course.save');

    // 同意选修课申请表
    Route::any('elective-course/approve','AddElectiveCourseController@approve')
        ->name('school_manager.elective-course.approve');

    // 拒绝选修课申请表
    Route::any('elective-course/refuse','AddElectiveCourseController@refuse')
        ->name('school_manager.elective-course.refuse');
    // 解散选修课
    Route::any('elective-course/dissolved','AddElectiveCourseController@dissolved')
        ->name('school_manager.elective-course.dissolved');

    // 管理员审批选修课的 action
    Route::get('elective-course/management','ElectiveCoursesController@management')
        ->name('school_manager.elective-course.manager');

    Route::get('elective-course/edit','ElectiveCoursesController@management')
        ->name('school_manager.elective-course.edit');

    // 删除选修课上课的时间地点项
    Route::post('elective-course/delete-arrangement','ElectiveCoursesController@delete_arrangement')
        ->name('school_manager.elective-course.delete-arrangement');

    //考勤管理-新
    Route::prefix('teacher-attendance')->group(function(){
        Route::get('manager', 'TeacherAttendance\AttendanceController@manager')
            ->name('school_manager.teacher-attendance.manager');
        Route::post('load-manager', 'TeacherAttendance\AttendanceController@load_manager')
            ->name('school_manager.teacher-attendance.load-manager');
        Route::post('load-attendance', 'TeacherAttendance\AttendanceController@load_attendance')
            ->name('school_manager.teacher-attendance.load-attendance');
        Route::post('save-exceptionday', 'TeacherAttendance\AttendanceController@save_exceptionday')
            ->name('school_manager.teacher-attendance.save-exceptionday');
        Route::post('delete-exceptionday', 'TeacherAttendance\AttendanceController@delete_exceptionday')
            ->name('school_manager.teacher-attendance.delete-exceptionday');
        Route::post('save-attendance', 'TeacherAttendance\AttendanceController@save_attendance')
            ->name('school_manager.teacher-attendance.save-attendance');
        Route::post('save-clocksets', 'TeacherAttendance\AttendanceController@save_clocksets')
            ->name('school_manager.teacher-attendance.save-clocksets');
        Route::any('load-clockins-daycount', 'TeacherAttendance\AttendanceController@load_clockins_daycount')
            ->name('school_manager.teacher-attendance.load-clockins-daycount');
        Route::any('load-clockins-monthcount', 'TeacherAttendance\AttendanceController@load_clockins_monthcount')
            ->name('school_manager.teacher-attendance.load-clockins-monthcount');
    });
    // 办公管理
    Route::prefix('oa')->group(function(){
        // 项目管理
        Route::get('projects-manager','OA\ProjectsController@management')
            ->name('school_manager.oa.projects-manager');
        Route::get('projects-manager/view','OA\ProjectsController@view')
            ->name('school_manager.oa.project-view');
        Route::get('projects-manager/task-view','OA\ProjectsController@task_view')
            ->name('school_manager.oa.task-view');
        Route::post('projects-manager/save','OA\ProjectsController@save')
            ->name('school_manager.oa.project-save');
        // 任务管理
        Route::get('tasks-manager','OA\ProjectsController@tasks')
            ->name('school_manager.oa.tasks-manager');
        // 来访管理
        Route::get('visitors-manager','OA\VisitorsController@management')
            ->name('school_manager.oa.visitors-manager');
        // 公文管理
        Route::get('documents-manager','ElectiveCoursesController@management')
            ->name('school_manager.oa.documents-manager');
        // 考勤管理
        Route::get('attendances-manager','OA\AttendanceTeacherController@management')
            ->name('school_manager.oa.attendances-manager');
        // 编辑考勤组
        Route::any('attendances-group/{id}','OA\AttendanceTeacherController@view')
            ->name('school_manager.oa.attendances-group');
        //添加考勤组
        Route::any('attendances-add-group','OA\AttendanceTeacherController@addGroup')
            ->name('school_manager.oa.attendances-add-group');
        //保存考勤组
        Route::any('attendances-save','OA\AttendanceTeacherController@save')
            ->name('school_manager.oa.attendances-save');
        //添加成员
        Route::any('attendances-members/{id}','OA\AttendanceTeacherController@members')
            ->name('school_manager.oa.attendances-members');
        Route::any('attendances-add-member','OA\AttendanceTeacherController@addMember')
            ->name('school_manager.oa.attendances-add-member');
        Route::any('attendances-del-member','OA\AttendanceTeacherController@delmember')
            ->name('school_manager.oa.attendances-del-member');
        //补卡
        Route::any('attendances-messages','OA\AttendanceTeacherController@messages')
            ->name('school_manager.oa.attendances-messages');
        Route::any('attendances-accept-messages','OA\AttendanceTeacherController@messageAccept')
            ->name('school_manager.oa.attendances-accept-messages');
        Route::any('attendances-reject-messages','OA\AttendanceTeacherController@messagereject')
            ->name('school_manager.oa.attendances-reject-messages');
        Route::any('attendances-total-list','OA\AttendanceTeacherController@attendanceCourseList')
            ->name('school_manager.oa.attendances-total');
        Route::any('attendances-total-export','OA\AttendanceTeacherController@export')
            ->name('school_manager.oa.attendances-export');
        // 审批管理
        Route::get('approval-manager','ElectiveCoursesController@management')
            ->name('school_manager.oa.approval-manager');
        // 通知公告
        Route::get('system-messages-manager','ElectiveCoursesController@management')
            ->name('school_manager.oa.system-messages-manager');
    });

    // 学生管理
    Route::prefix('students')->group(function(){
        // 申请管理
        Route::get('applications-manager','Applications\ApplicationController@list')
            ->name('school_manager.students.applications-manager');
        // 编辑申请
        Route::any('applications-edit','Applications\ApplicationController@edit')
            ->name('school_manager.students.applications-edit');
        // 申请设置
        Route::get('applications-set','Applications\ApplicationTypeController@list')
            ->name('school_manager.students.applications-set');
        Route::get('applications-set-add','Applications\ApplicationTypeController@add')
            ->name('school_manager.students.applications-set-add');
        // 设置保存
        Route::any('applications-set-save','Applications\ApplicationTypeController@save')
            ->name('school_manager.students.applications-set-save');
        // 设置详情
        Route::get('applications-set-info','Applications\ApplicationTypeController@edit')
            ->name('school_manager.students.applications-set-info');


        // 签到管理
        Route::get('check-in-manager','ElectiveCoursesController@management')
            ->name('school_manager.students.check-in-manager');
        // 选修课审批
        Route::get('performances-manager','ElectiveCoursesController@management')
            ->name('school_manager.students.performances-manager');

        // 评分管理 evaluation-score
        Route::get('evaluation-score','Evaluate\EvaluationScoreController@index')
            ->name('school_manager.students.evaluation-score-index');
        // 评分详情列表
        Route::get('details-list','Evaluate\EvaluationScoreController@details')
            ->name('school_manager.students.evaluation-details-list');
    });

    // 内容管理
    Route::prefix('contents')->group(function (){
        // 日常安排
        Route::get('regular-manager','ElectiveCoursesController@management')
            ->name('school_manager.contents.regular-manager');

        // 调查问卷
        Route::get('questionnaire/list','QuestionnaireController@management')
            ->name('school_manager.contents.questionnaire');
        Route::get('questionnaire/add','QuestionnaireController@add')
            ->name('school_manager.contents.questionnaire.add');
        Route::get('questionnaire/edit/{id}','QuestionnaireController@edit')
            ->name('school_manager.contents.questionnaire.edit');
        Route::post('questionnaire/update','QuestionnaireController@update')->name('school_manager.contents.questionnaire.update');
        Route::get('questionnaire/delete/{id}','QuestionnaireController@delete')->name('school_manager.contents.questionnaire.delete');

        // 科技成功
        Route::get('science-list','Contents\ScienceController@list')
            ->name('school_manager.contents.science.list');
        // 添加
        Route::any('science-add','Contents\ScienceController@create')
            ->name('school_manager.contents.science.add');
        // 编辑
        Route::any('science-edit','Contents\ScienceController@edit')
            ->name('school_manager.contents.science.edit');
        // 删除
        Route::any('science-delete','Contents\ScienceController@delete')
            ->name('school_manager.contents.science.delete');

        // 相册管理
        Route::get('photo-album-manager','Contents\NewsController@photo_album')
            ->name('school_manager.contents.photo-album');
        Route::get('delete-album','Contents\NewsController@delete_album')
            ->name('school_manager.contents.delete-album');
        Route::post('create-album','Contents\NewsController@create_album')
            ->name('school_manager.contents.create-album');

        // 校园简介
        Route::get('campus-intro','Contents\NewsController@campus_intro')
            ->name('school_manager.contents.campus-intro');
        Route::post('save-campus-intro','Contents\NewsController@save_campus_intro')
		            ->name('school_manager.contents.save-campus-intro');
        Route::get('get-campus-video','Contents\NewsController@get_campus_video')
            ->name('school_manager.contents.get-campus-video'); // 获取视频
        Route::post('save-campus-video','Contents\NewsController@save_campus_video')
            ->name('school_manager.contents.save-campus-video'); // 保存视频

        // 动态管理
        Route::get('news-manager','Contents\NewsController@management')
            ->name('school_manager.contents.news-manager');
        Route::post('news/save','Contents\NewsController@save')
            ->name('school_manager.contents.news.save');

        Route::post('news/publish','Contents\NewsController@publish')
            ->name('school_manager.contents.news.publish');

        Route::post('news/delete','Contents\NewsController@delete')
            ->name('school_manager.contents.news.delete');
        Route::any('news/load','Contents\NewsController@load')
            ->name('school_manager.contents.news.load');
        Route::post('news/save-section','Contents\NewsController@save_section')
            ->name('school_manager.contents.news.save-section');

        Route::post('news/delete-section','Contents\NewsController@delete_section')
            ->name('school_manager.contents.news.delete-section');

        Route::post('news/move-up-section','Contents\NewsController@move_up_section')
            ->name('school_manager.contents.news.move-up-section');

        Route::post('news/move-down-section','Contents\NewsController@move_down_section')
            ->name('school_manager.contents.news.move-down-section');
    });

    // banner 展示
    Route::get('banner/list','BannerController@index')->name('school_manager.banner.list');

    // banner 添加页面展示
    Route::get('banner/delete','BannerController@delete')->name('school_manager.banner.delete');

    // banner 排序
    Route::get('banner/top-banner-sort','BannerController@top_banner_sort')->name('school_manager.banner.top_banner_sort');


    Route::any('banner/get-type','BannerController@get_type')->name('school_manager.banner.get_type');// 获取分类
    Route::post('banner/save-banner','BannerController@save_banner')->name('school_manager.banner.save_banner');// 保存数据
    Route::post('banner/get-banner-one','BannerController@get_banner_one')->name('school_manager.banner.get_banner_one');// 获取数据

  // 通知管理
    Route::prefix('notice')->group(function (){
        // 添加
        Route::get('news-notice','NoticeController@add')
            ->name('school_manager.notice.add');

        // 展示
        Route::get('show-notice', 'NoticeController@index')
            ->name('school_manager.notice.list');

        // 详情
        Route::get('details-notice', 'NoticeController@details')
            ->name('school_manager.notice.details');

        // 保存数据
        Route::post('save-notice', 'NoticeController@save')
            ->name('school_manager.notice.save');

        // 加载数据
        Route::post('load', 'NoticeController@load')
            ->name('school_manager.notice.load');

        // 删除数据
        Route::get('delete', 'NoticeController@delete')->name('school_manager.notice.delete');

        Route::post('delete-media', 'NoticeController@delete_media')
            ->name('school_manager.notice.delete-media');
    });

    //值周功能
    Route::get('attendance/delete', 'AttendanceSchedulesController@delete')->name('school_manager.attendance.delete');                        // 添加校区
    Route::get('attendance/add', 'AttendanceSchedulesController@add')->name('school_manager.attendance.add');                        // 添加校区
    Route::get('attendance/edit/{id}', 'AttendanceSchedulesController@edit')->name('school_manager.attendance.edit');                     // 编辑校区
    Route::post('attendance/update', 'AttendanceSchedulesController@update')->name('school_manager.attendance.update');              // 保存校区
    Route::get('attendance/list', 'AttendanceSchedulesController@index')->name('school_manager.attendance.list');

    Route::get('attendance/timeslots/edit/{taskid}', 'AttendanceSchedulesController@editTimeSlots')
        ->name('school_manager.attendance.timeslots.edit');
    Route::post('attendance/timeslots/update', 'AttendanceSchedulesController@updateTimeSlots')
        ->name('school_manager.attendance.timeslots.update');

    Route::get('attendance/schedule/display/{taskid}', 'AttendanceSchedulesController@display')
        ->name('school_manager.attendance.schedule.display');
    Route::post('attendance/schedule/update', 'AttendanceSchedulesController@createSchedules')
        ->name('school_manager.attendance.schedule.update');

    Route::get('attendance/person/add/{id}', 'AttendanceSchedulesController@addPerson')
        ->name('school_manager.attendance.person.add');
    Route::get('attendance/person/search/{id}', 'AttendanceSchedulesController@searchPerson')
        ->name('school_manager.attendance.person.search');

    // 学校的基本配置
    Route::prefix('configs')->group(function(){
        Route::get('performance-teacher','Configs\PerformancesController@teachers')
            ->name('school_manger.configs.performance-teacher');
        Route::get('performance-teacher-delete','Configs\PerformancesController@teacher_delete')
            ->name('school_manger.configs.performance-teacher-delete');
        Route::post('performance-teacher-save','Configs\PerformancesController@teacher_save')
            ->name('school_manger.configs.performance-teacher-save');
    });

    // 教师档案管理
    Route::prefix('teachers')->group(function(){
        Route::get('add-new','Teachers\ProfilesController@add_new')
            ->name('school_manager.teachers.add-new');
        Route::get('edit-profile','Teachers\ProfilesController@edit')
            ->name('school_manager.teachers.edit-profile');
        Route::any('edit-avatar','Teachers\ProfilesController@avatar')
            ->name('school_manager.teachers.edit-avatar');
        Route::post('save-profile','Teachers\ProfilesController@save')
            ->name('school_manager.teachers.save-profile');
        Route::get('manage-performance','Teachers\ProfilesController@manage_performance')
            ->name('school_manager.teachers.manage-performance');
        Route::post('manage-performance-save','Teachers\ProfilesController@manage_performance_save')
            ->name('school_manager.teachers.manage-performance-save');
        Route::any('export','Teachers\ProfilesController@export')
            ->name('school_manager.teachers.export');

        // 评聘列表
        Route::any('list-qualification','Teachers\ProfilesController@listQualification')
            ->name('school_manager.teachers.list.qualification');
        // 评聘添加页面
        Route::any('add-qualification','Teachers\ProfilesController@addQualification')
            ->name('school_manager.teachers.add.qualification');
        // 评聘保存
        Route::any('save-qualification','Teachers\ProfilesController@saveQualification')
            ->name('school_manager.teachers.save.qualification');
        // 评聘删除
        Route::any('save-qualification','Teachers\ProfilesController@delQualification')
            ->name('school_manager.teachers.del.qualification');
    });

    // 工作流程管理
    Route::prefix('pipeline')->group(function(){
        Route::get('flows/manager','Pipeline\FlowsController@manager')
            ->name('school_manager.pipeline.flows-manager');
        Route::get('flows/handler','Pipeline\FlowsController@handler')
            ->name('school_manager.pipeline.flows-handler');
        Route::get('flows/option','Pipeline\FlowsController@option')
            ->name('school_manager.pipeline.flows-option');

        Route::post('flows/load-flows', 'Pipeline\FlowsController@load_flows')
            ->name('school_manager.pipeline.load-flows');
        Route::post('flows/load-nodes','Pipeline\FlowsController@load_nodes')
            ->name('school_manager.pipeline.load-nodes');
        Route::post('flows/save-flow','Pipeline\FlowsController@save_flow')
            ->name('school_manager.pipeline.save-flow');
        Route::post('flows/delete-flow','Pipeline\FlowsController@delete_flow')
            ->name('school_manager.pipeline.delete-flow');
        Route::post('flows/save-node','Pipeline\FlowsController@save_node')
            ->name('school_manager.pipeline.save-node');
        Route::post('flows/delete-node','Pipeline\FlowsController@delete_node')
            ->name('school_manager.pipeline.delete-node');
        Route::post('flows/save-copy', 'Pipeline\FlowsController@save_copy')
            ->name('school_manager.pipeline.save-copy');
        Route::post('flows/delete-copy', 'Pipeline\FlowsController@delete_copy')
            ->name('school_manager.pipeline.delete-copy');
        Route::post('flows/save-option','Pipeline\FlowsController@save_option')
            ->name('school_manager.pipeline.save-option');
        Route::post('flows/delete-option','Pipeline\FlowsController@delete_option')
            ->name('school_manager.pipeline.delete-option');
        Route::post('flows/load-business','Pipeline\FlowsController@load_business')
            ->name('school_manager.pipeline.load-business');
        Route::post('flows/load-titles','Pipeline\FlowsController@load_titles')
            ->name('school_manager.pipeline.load-titles');
        Route::post('flows/save-auto-processed','Pipeline\FlowsController@save_auto_processed')
            ->name('school_manager.pipeline.save-auto-processed');
    });


    // 评教 评学 模块
    Route::prefix('evaluate')->group(function(){
        // 列表
        Route::get('content-list','Evaluate\EvaluateController@list')
            ->name('school_manager.evaluate.content-list');
        // 创建
        Route::any('content-create','Evaluate\EvaluateController@create')
            ->name('school_manager.evaluate.content-create');
        // 编辑
        Route::any('content-edit','Evaluate\EvaluateController@edit')
            ->name('school_manager.evaluate.content-edit');
        // 删除
        Route::get('delete','Evaluate\EvaluateController@delete')
            ->name('school_manager.evaluate.content-delete');
        // 评教列表
        Route::get('evaluate-teacher/list','Evaluate\EvaluateTeacherController@list')
            ->name('school_manager.evaluate.teacher-list');
        // 评教详情
        Route::get('evaluate-record/list','Evaluate\EvaluateRecordController@list')
            ->name('school_manager.evaluate.record-list');
        // 班级列表
        Route::get('evaluate-teacher/grade','Evaluate\EvaluateTeacherController@grade')
            ->name('school_manager.evaluate-teacher.grade');
        // 学生列表
        Route::get('evaluate-teacher/student','Evaluate\EvaluateTeacherController@student')
            ->name('school_manager.evaluate.student-list');
        // 创建
        Route::post('evaluate-teacher/create','Evaluate\EvaluateTeacherController@create')
            ->name('school_manager.evaluate.evaluate-teacher.create');

        // 评学
        Route::get('/evaluate-student-list','Evaluate\EvaluateController@evaluateStudentList')
            ->name('school_manager.evaluate.student.list');
        // 评学添加
        Route::any('/evaluate-student-add','Evaluate\EvaluateController@evaluateStudentAdd')
            ->name('school_manager.evaluate.student.add');
        // 评学编辑
        Route::any('evaluate-student-edit','Evaluate\EvaluateController@evaluateStudentEdit')
            ->name('school_manager.evaluate.student.edit');
        // 评学删除
        Route::get('evaluate-student-delete','Evaluate\EvaluateController@evaluateStudentDelete')
            ->name('school_manager.evaluate.student.delete');
    });
    Route::prefix('importer')->group(function(){
        Route::any('manager', 'ImporterController@manager')->name('school_manager.importer.manager');
        Route::any('update', 'ImporterController@update')->name('school_manager.importer.update');
        Route::any('add', 'ImporterController@add')->name('school_manager.importer.add');
        Route::any('edit', 'ImporterController@edit')->name('school_manager.importer.edit');
        Route::any('handle/{id}', 'ImporterController@handle')->name('school_manager.importer.handle');
        Route::any('result/{id}', 'ImporterController@result')->name('school_manager.importer.result');
    });

    // 会议
    Route::prefix('meeting')->group(function (){
        // 会议列表
        Route::get('list','OA\NewMeetingController@index')
            ->name('school_manager.meeting.list');
    });

});

