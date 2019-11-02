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

    // 校区的管理
    Route::get('campus/add', 'CampusController@add')->name('school_manager.campus.add');                        // 添加校区
    Route::get('campus/edit', 'CampusController@edit')->name('school_manager.campus.edit');                     // 编辑校区
    Route::post('campus/update', 'CampusController@update')->name('school_manager.campus.update');              // 保存校区
    Route::get('campus/users', 'UsersController@users')->name('school_manager.campus.users');                   // 显示校区的学生/教师列表

    Route::get('campus/buildings', 'CampusController@buildings')->name('school_manager.campus.buildings');   // 显示校区的包含的建筑信息
    Route::get('campus/institutes', 'CampusController@institutes')->name('school_manager.campus.institutes');   // 显示校区的包含的学院的信息

    // 学院的管理
    Route::get('institute/edit', 'InstitutesController@edit')->name('school_manager.institute.edit');           // 编辑学院
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
    Route::get('timetable/manager/courses', 'TimeTables\CoursesController@manager')->name('school_manager.courses.manager');

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


    // 招生管理
    Route::get('planRecruit/list', 'RecruitStudent\PlanRecruit@list')->name('school_manager.planRecruit.list');


    Route::get('registration/list', 'RecruitStudent\RegistrationInformatics@index')->name('school_manager.registration.list');  // 报名列表


    //设备管理
//    Route::get('')-name();

});
