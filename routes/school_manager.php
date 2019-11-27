<?php

Route::prefix('school_manager')->group(function () {
    // 学校管理
    Route::get('school/view', 'CampusController@school')->name('school_manager.school.view'); // 显示学校的统计信息
    Route::get('school/institutes', 'SchoolsController@institutes')->name('school_manager.school.institutes'); // 显示学校的所有学院
    Route::get('school/departments', 'SchoolsController@departments')->name('school_manager.school.departments'); // 显示学校的所有系
    Route::get('school/majors', 'SchoolsController@majors')->name('school_manager.school.majors'); // 显示学校的所有专业
    Route::get('school/grades', 'SchoolsController@grades')->name('school_manager.school.grades'); // 显示学校的所有班级
    Route::get('school/teachers', 'SchoolsController@teachers')->name('school_manager.school.teachers'); // 显示学校的所有老师
    Route::get('school/students', 'SchoolsController@students')->name('school_manager.school.students'); // 显示学校的所有学生
    Route::get('school/rooms', 'SchoolsController@rooms')->name('school_manager.school.rooms'); // 显示学校的所有学生
    Route::get('school/organization-manager', 'SchoolsController@organization')
        ->name('school_manager.school.organization-manager'); // 显示学校的组织架构
    Route::post('organizations/load-parent', 'SchoolsController@load_parent')
        ->name('school_manager.organizations.load-parent'); // 加载某一级别机构的上级
    Route::post('organizations/save', 'SchoolsController@save_organization')
        ->name('school_manager.organizations.save'); // 保存学校的组织架构
    Route::post('organizations/load', 'SchoolsController@load_organization')
        ->name('school_manager.organizations.load'); // 加载 单个 学校的组织架构
    Route::post('organizations/delete', 'SchoolsController@delete_organization')
        ->name('school_manager.organizations.delete'); // 加载 单个 学校的组织架构

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
    Route::get('grade/users', 'GradesController@users')->name('school_manager.grade.users');     // 班级的学生列表

    // 学生管理: 只有 学校管理员以上级别的角色才可以添加,编辑,学生school_manager.scenery.edit
    Route::get('student/add', 'StudentsController@add')->name('school_manager.student.add');                // 添加学生
    Route::get('student/edit', 'StudentsController@edit')->name('school_manager.student.edit');             // 编辑学生
    Route::get('student/suspend', 'StudentsController@suspend')->name('school_manager.student.suspend');    // 休学
    Route::get('student/stop', 'StudentsController@stop')->name('school_manager.student.stop');             // 停课
    Route::get('student/reject', 'StudentsController@reject')->name('school_manager.student.reject');       // 退学
    Route::post('student/update', 'StudentsController@update')->name('school_manager.student.update');      // 保存学生

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

    // 领取教材
    Route::get('textbook-get', 'TextbookController@getTextbook')
        ->name('school_manager.textbook.get');


    // 校历事件添加
    Route::any('calendar/save', 'Calendar\IndexController@save')->name('school_manger.school.calendar.save');

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

    // 管理员审批选修课的 action
    Route::get('elective-course/management','ElectiveCoursesController@management')
        ->name('school_manager.elective-course.manager');

    Route::get('elective-course/edit','ElectiveCoursesController@management')
        ->name('school_manager.elective-course.edit');

    // 删除选修课上课的时间地点项
    Route::post('elective-course/delete-arrangement','ElectiveCoursesController@delete_arrangement')
        ->name('school_manager.elective-course.delete-arrangement');

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
        Route::get('attendances-manager','ElectiveCoursesController@management')
            ->name('school_manager.oa.attendances-manager');
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
        // 评分管理
        Route::get('performances-manager','ElectiveCoursesController@management')
            ->name('school_manager.students.performances-manager');
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
        Route::post('questionnaire/update','QuestionnaireController@update')
            ->name('school_manager.contents.questionnaire.update');

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

        // 动态管理
        Route::get('news-manager','Contents\NewsController@management')
            ->name('school_manager.contents.news-manager');
        Route::post('news/save','Contents\NewsController@save')
            ->name('school_manager.contents.news.save');
        Route::post('news/delete','Contents\NewsController@delete')
            ->name('school_manager.contents.news.delete');
        Route::any('news/load','Contents\NewsController@load')
            ->name('school_manager.contents.news.load');
        Route::post('news/save-section','Contents\NewsController@save_section')
            ->name('school_manager.contents.news.save-section');
    });

    // banner 展示
    Route::get('banner/list','BannerController@index')->name('school_manager.banner.list');

    // banner 添加页面展示
    Route::get('banner/add','BannerController@add')->name('school_manager.banner.add');

    // banner 修改页面展示
    Route::get('banner/edit','BannerController@edit')->name('school_manager.banner.edit');

    // banner 保存数据
    Route::post('banner/save','BannerController@save')->name('school_manager.banner.save');

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

        // 修改
        Route::get('edit-notice', 'NoticeController@edit')
            ->name('school_manager.notice.edit');

        // 保存数据
        Route::post('save-notice', 'NoticeController@save')
            ->name('school_manager.notice.save');
    });



});

