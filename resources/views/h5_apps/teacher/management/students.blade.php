@extends('layouts.h5_teacher_app')
@section('content')
    <div id="app-init-data-holder" data-school="{{ $teacher->getSchoolId() }}"></div>
    <div id="school-teacher-management-entry" class="school-intro-container">
        <div class="main p-15">
            <h3 class="title">班级管理</h3>
            <div class="row">
                <div class="col-4">
                    <a href="{{ route('h5.teacher.management.my-students') }}" class="no-dec">
                        <p class="text-center">
                            <img src="{{ asset('assets/img/pipeline/t3@2x.png') }}" class="icon-image">
                        </p>
                        <p class="text-center text-dark mt-adjust">学生信息</p>
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
            <h3 class="title">审批管理</h3>
            <div class="row">
                <div class="col-4">
                    <p class="text-center">
                        <img src="{{ asset('assets/img/pipeline/t4@2x.png') }}" class="icon-image">
                    </p>
                    <p class="text-center text-dark mt-adjust">待审批</p>
                </div>
                <div class="col-4">
                    <p class="text-center">
                        <img src="{{ asset('assets/img/pipeline/t5@2x.png') }}" class="icon-image">
                    </p>
                    <p class="text-center text-dark mt-adjust">已审批</p>
                </div>
            </div>
            <div class="clearfix"></div>
            <h3 class="title">教学管理</h3>
            <div class="row">
                <div class="col-4">
                    <p class="text-center">
                        <img src="{{ asset('assets/img/pipeline/t6@2x.png') }}" class="icon-image">
                    </p>
                    <p class="text-center text-dark mt-adjust">考勤管理</p>
                </div>
                <div class="col-4">
                    <p class="text-center">
                        <img src="{{ asset('assets/img/pipeline/t7@2x.png') }}" class="icon-image">
                    </p>
                    <p class="text-center text-dark mt-adjust">成绩管理</p>
                </div>
            </div>
            <div class="clearfix"></div>
            @if($teacher->isSchoolAdminOrAbove())
                <h3 class="title">管理员</h3>
                <div class="row">
                    <div class="col-4">
                        <p class="text-center">
                            <img src="{{ asset('assets/img/pipeline/t8@2x.png') }}" class="icon-image">
                        </p>
                        <p class="text-center text-dark mt-adjust">管理员</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
