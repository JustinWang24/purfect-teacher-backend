<?php

# 该文件用于存放教师端路由

Route::prefix('teacher')->group(function () {

    Route::get('index', 'SchoolSceneryController@index')->name('teacher.scenery.index');       // 校园风采
    Route::get('profile', 'SchoolSceneryController@profile')->name('teacher.scenery.profile'); // 学校简介

    // 课件管理
    Route::get('course/materials/manager', 'Course\MaterialsController@manager')->name('teacher.course.materials.manager'); //
    Route::post('course/materials/create', 'Course\MaterialsController@create')->name('teacher.course.materials.create'); // 教师添加课件
    Route::post('course/materials/load', 'Course\MaterialsController@load')->name('teacher.course.materials.load'); // 教师加载课件
    Route::post('course/materials/delete', 'Course\MaterialsController@delete')->name('teacher.course.materials.delete'); // 教师删除课件


    Route::get('conference/index', 'ConferenceController@index')->name('teacher.conference.index');    // 会议列表
    Route::any('conference/create', 'ConferenceController@create')->name('teacher.conference.create'); // 添加接口
    Route::any('conference/edit', 'ConferenceController@edit')->name('teacher.conference.edit');       // 添加接口
    Route::get('conference/delete', 'ConferenceController@delete')->name('teacher.conference.delete'); // 删除会议

    //分组
    Route::get('group/list', 'GroupController@list')->name('teacher.group.list');



    Route::get('preset/step', 'OfficialDocumentController@presetStep')->name('teacher.get.preset.step');                    // 所有系统预置步骤
    Route::post('production/process', 'OfficialDocumentController@productionProcess')->name('teacher.production.process');  // 生成公文流程
    Route::get('list/documents', 'OfficialDocumentController@listAll')->name('teacher.list.official.documents');        // 学校公文列表
    Route::get('official/document', 'OfficialDocumentController@getProcess')->name('teacher.get.official.document');        // 学校公文列表
    Route::get('one/process', 'OfficialDocumentController@getProcessDetails')->name('teacher.get.one.process');             // 获取公文流程详情
    Route::post('add/step_user', 'OfficialDocumentController@addStepUser')->name('teacher.add.step.user');                  // 添加流程中的步骤负责人
    Route::post('update/step_user', 'OfficialDocumentController@updateStepUser')->name('teacher.update.step.user');         // 修改流程中的步骤负责人

    Route::any('grade/set-monitor', 'GradesController@set_monitor')->name('teacher.grade.set-monitor');     // 设置班长

    Route::get('grade/users', 'GradesController@users')->name('teacher.grade.users');     // 班级的学生列表

    // 更新密码
    Route::any('profile/update-password', 'GradesController@update_password')
        ->name('teacher.profile.update-password');
    Route::any('profile/edit', 'GradesController@edit')
        ->name('teacher.profile.edit');

    //考试管理
    Route::get('exam/index', 'ExamController@index')->name('teacher.exam.index');     // 列表
    Route::get('exam/create', 'ExamController@create')->name('teacher.exam.create');  // 创建考试
    // 创建考试计划
    Route::get('exam/createExamPlan', 'ExamController@createExamPlan')->name('teacher.exam.createExamPlan');
    // 获取学校下面的系
    Route::get('exam/getDepartmentList', 'ExamController@getDepartmentList')->name('teacher.exam.getDepartmentList');
    // 获取系下面的专业
    Route::get('exam/getMajorList', 'ExamController@getMajorList')->name('teacher.exam.getMajorList');
    // 获取空闲的教室
    Route::get('exam/getLeisureRoom', 'ExamController@getLeisureRoom')->name('teacher.exam.getLeisureRoom');
    // 创建考点
    Route::get('exam/createPlanRoom', 'ExamController@createPlanRoom')->name('teacher.exam.createPlanRoom');
    //绑定考点老师
    Route::get('exam/roomBindingTeacher', 'ExamController@roomBindingTeacher')->name('teacher.exam.roomBindingTeacher');

    // 获取专业下的班级
    Route::get('exam/getGradeList', 'ExamController@getGradeList')->name('teacher.exam.getGradeList');
    Route::get('exam/data', 'ExamController@data')->name('teacher.exam.data');                           // 创建考试
    Route::get('exam/getClassRooms', 'ExamController@getClassRooms')->name('teacher.exam.getClassRooms');// 获取教室列表
    Route::get('exam/getCourses', 'ExamController@getCourses')->name('teacher.exam.getCourses');         // 获取课程列表

    // 报名表管理
    Route::get('planRecruit/list', 'RecruitmentFormsController@list')->name('teacher.planRecruit.list');

    Route::get('registration-forms/manage', 'RecruitmentFormsController@manage')
        ->name('teacher.registration.forms.manage');

    // 查看考生的报名表
    Route::get('registration-forms/view-student-profile', 'RecruitmentFormsController@view_student_profile')
        ->name('teacher.registration.view');

    //教材管理
    Route::get('textbook/manager', 'TextbookController@manager')->name('teacher.textbook.manager');     // 教材管理界面
    Route::get('textbook/add', 'TextbookController@add')->name('teacher.textbook.add');     // 添加教程
    Route::get('textbook/edit', 'TextbookController@edit')->name('teacher.textbook.edit');  // 编辑教程
    Route::post('textbook/delete', 'TextbookController@delete')->name('teacher.textbook.delete'); // 删除教程
    Route::post('textbook/save', 'TextbookController@save')->name('teacher.textbook.save'); // 保存教程
    Route::post('textbook/search', 'TextbookController@search')->name('teacher.textbook.search'); // 保存教程
    Route::get('textbook/list', 'TextbookController@list')->name('teacher.textbook.list');  // 教程列表

    Route::any('textbook/list-paginate', 'TextbookController@list_paginate')
        ->name('teacher.textbook.list_paginate');  // 以分页的方式, 教程列表

    Route::any('textbook/update-related-courses', 'TextbookController@update_related_courses')
        ->name('teacher.textbook.update_related_courses');  // 保存教材关联的课程

    Route::get('textbook/loadMajorTextbook', 'TextbookController@loadMajorTextbook')
        ->name('teacher.textbook.loadMajorTextbook'); // 获取专业教材采购情况

    Route::get('textbook/loadGradeTextbook', 'TextbookController@loadGradeTextbook')
        ->name('teacher.textbook.loadGradeTextbook'); // 获取班级教材采购情况

    Route::get('textbook/gradeTextbookDownload', 'TextbookController@gradeTextbookDownload')
        ->name('teacher.textbook.gradeTextbookDownload'); // 班级教材下载

    Route::post('textbook/courseBindingTextbook', 'TextbookController@courseBindingTextbook')
        ->name('teacher.textbook.courseBindingTextbook'); // 课程绑定教材

    // 删除考生的报名表
    Route::get('registration-forms/delete', 'RecruitmentFormsController@delete')
        ->name('teacher.registration.delete');

    Route::get('registration-forms/print-invitation', 'RecruitmentFormsController@print_invitation')
        ->name('teacher.print.invitation');

    Route::get('registration-forms/cancel-enrolment', 'RecruitmentFormsController@cancel_enrolment')
        ->name('teacher.cancel.enrolment');

    // 查看已经批准的报名表
    Route::get('registration-forms/enrol', 'RecruitmentFormsController@enrol')
        ->name('teacher.registration.forms.enrol');

    // 教师申请开设一门选修课
    Route::get('elective-course/create', 'ElectiveCoursesController@create')
        ->name('teacher.elective-course.create');
    Route::get('elective-course/edit', 'ElectiveCoursesController@edit')
        ->name('teacher.elective-course.edit');

    // 社区管理
    // 动态列表
    Route::get('/dynamic','Community\DynamicController@index')
        ->name('teacher.community.dynamic');
    // 动态详情
    Route::any('/dynamic-edit','Community\DynamicController@edit')
        ->name('teacher.dynamic.edit');
    // 删除
    Route::get('/delete','Community\DynamicController@delete')
        ->name('teacher.dynamic.delete');

    // 社区分类列表
    Route::get('/dynamic-type','Community\TypeController@index')
        ->name('teacher.community.dynamic.type');
    // 社区分类添加页面
    Route::any('/dynamic-type-add','Community\TypeController@add')
        ->name('teacher.community.dynamic.type.add');
    // 社区分类保存
    Route::any('/dynamic-type-save','Community\TypeController@save')
        ->name('teacher.community.dynamic.type.save');
    // 社团列表
    Route::get('/communities-list','Community\CommunitiesController@list')
        ->name('teacher.community.communities');
    // 社团详情
    Route::any('/communities-edit','Community\CommunitiesController@edit')
        ->name('teacher.communities.edit');
    // 删除社团
    Route::get('/communities-delete','Community\CommunitiesController@delete')
        ->name('teacher.communities.delete');
    // 社团成员
    Route::get('/communities-members','Community\CommunitiesController@members')
        ->name('teacher.communities.members');

    // 老师编辑学生的档案照片
    Route::any('/student/edit-avatar','StudentsController@edit_avatar')
        ->name('teacher.student.edit-avatar');

    // 一码通
    // 使用列表
    Route::get('/code-list','Code\CodeController@list')
        ->name('teacher.code.list');
});
