@extends('layouts.h5_teacher_app')
@section('content')
    <div id="app-init-data-holder" data-school="{{ $teacher->getSchoolId() }}"></div>
    <div id="school-teacher-management-entry" class="school-intro-container">
        <div class="main pt-20">
            <div class="row nav-bar mt-20">
                <div class="col-6">
                    <a href="{{ route('h5.teacher.management.view',['api_token'=>$api_token,'type'=>'teacher']) }}" class="no-dec">
                        <p class="text-center text-dark mt-adjust {{ $type=='teacher' ? 'text-blue' : null }}">教师助手</p>
                        @if($type === 'teacher')
                            <p class="text-center" style="margin-top: -20px;">
                                <img src="{{ asset('assets/img/angle.png') }}">
                            </p>
                        @endif
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('h5.teacher.management.view',['api_token'=>$api_token,'type'=>'employee']) }}" class="no-dec">
                        <p class="text-center text-dark mt-adjust {{ $type=='employee' ? 'text-blue' : null }}">教工助手</p>
                        @if($type === 'employee')
                        <p class="text-center" style="margin-top: -20px;">
                            <img src="{{ asset('assets/img/angle.png') }}">
                        </p>
                        @endif
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>

            @if($type === 'teacher')
                <h3 class="title">教学助手</h3>
                <div class="row">
                    <div class="col-4">
                        <a href="{{ route('h5.teacher.management.my-students',['api_token'=>$api_token]) }}" class="no-dec">
                            <p class="text-center">
                                <img src="{{ asset('assets/img/teacher/ass-icon1.png') }}" class="icon-image">
                            </p>
                            <p class="text-center text-dark mt-adjust">教学计划</p>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="{{ route('h5.teacher.management.my-students',['api_token'=>$api_token]) }}" class="no-dec">
                            <p class="text-center">
                                <img src="{{ asset('assets/img/teacher/ass-icon2.png') }}" class="icon-image">
                            </p>
                            <p class="text-center text-dark mt-adjust">教案计划</p>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="{{ route('h5.teacher.management.my-students',['api_token'=>$api_token]) }}" class="no-dec">
                            <p class="text-center">
                                <img src="{{ asset('assets/img/teacher/ass-icon3.png') }}" class="icon-image">
                            </p>
                            <p class="text-center text-dark mt-adjust">签到评分</p>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <a href="{{ route('h5.teacher.management.my-students',['api_token'=>$api_token]) }}" class="no-dec">
                            <p class="text-center">
                                <img src="{{ asset('assets/img/teacher/ass-icon4.png') }}" class="icon-image">
                            </p>
                            <p class="text-center text-dark mt-adjust">课程表</p>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="{{ route('h5.teacher.management.my-students',['api_token'=>$api_token]) }}" class="no-dec">
                            <p class="text-center">
                                <img src="{{ asset('assets/img/teacher/ass-icon5.png') }}" class="icon-image">
                            </p>
                            <p class="text-center text-dark mt-adjust">课件</p>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="{{ route('h5.teacher.management.my-students',['api_token'=>$api_token]) }}" class="no-dec">
                            <p class="text-center">
                                <img src="{{ asset('assets/img/teacher/ass-icon6.png') }}" class="icon-image">
                            </p>
                            <p class="text-center text-dark mt-adjust">课程</p>
                        </a>
                    </div>
                </div>
                <hr>
                <div class="clearfix"></div>
                <hr>
                <h3 class="title">教务助手</h3>
                <div class="row">
                    <div class="col-4">
                        <p class="text-center">
                            <img src="{{ asset('assets/img/teacher/ass7.png') }}" class="icon-image">
                        </p>
                        <p class="text-center text-dark mt-adjust">考试</p>
                    </div>
                    <div class="col-4">
                        <p class="text-center">
                            <img src="{{ asset('assets/img/teacher/ass8.png') }}" class="icon-image">
                        </p>
                        <p class="text-center text-dark mt-adjust">成绩</p>
                    </div>
                    <div class="col-4">
                        <p class="text-center">
                            <img src="{{ asset('assets/img/teacher/ass9.png') }}" class="icon-image">
                        </p>
                        <p class="text-center text-dark mt-adjust">选课</p>
                    </div>
                </div>
                <div class="clearfix"></div>
                <hr>
                <h3 class="title">班主任助手</h3>
                <div class="row">
                    <div class="col-4">
                        <p class="text-center">
                            <img src="{{ asset('assets/img/teacher/ass10.png') }}" class="icon-image">
                        </p>
                        <p class="text-center text-dark mt-adjust">班级管理</p>
                    </div>
                    <div class="col-4">
                        <p class="text-center">
                            <img src="{{ asset('assets/img/teacher/ass11.png') }}" class="icon-image">
                        </p>
                        <p class="text-center text-dark mt-adjust">学生管理</p>
                    </div>
                    <div class="col-4">
                        <p class="text-center">
                            <img src="{{ asset('assets/img/teacher/ass12.png') }}" class="icon-image">
                        </p>
                        <p class="text-center text-dark mt-adjust">学生申请</p>
                    </div>
                </div>
                <div class="clearfix"></div>
                <hr>
                <h3 class="title">管理分析助手</h3>
                <div class="row">
                    <div class="col-4">
                        <p class="text-center">
                            <img src="{{ asset('assets/img/teacher/ass20.png') }}" class="icon-image">
                        </p>
                        <p class="text-center text-dark mt-adjust">数据分析</p>
                    </div>
                </div>
                <div class="clearfix"></div>
                <hr>
                <h3 class="title">监管助手</h3>
                <div class="row">
                    <div class="col-6">
                        <p class="text-center p-15" style="margin-top:0;padding-top:0;">
                            <img src="{{ asset('assets/img/teacher/ass22.png') }}" style="width: 100%;">
                        </p>
                    </div>
                </div>
                <div class="clearfix"></div>
            @endif

            @if($type === 'employee')
                <h3 class="title">安全管理</h3>
                <div class="row">
                    <div class="col-4">
                        <a href="{{ route('h5.teacher.management.my-students',['api_token'=>$api_token]) }}" class="no-dec">
                            <p class="text-center">
                                <img src="{{ asset('assets/img/teacher/ass13.png') }}" class="icon-image">
                            </p>
                            <p class="text-center text-dark mt-adjust">出入管理</p>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="{{ route('h5.teacher.management.my-students',['api_token'=>$api_token]) }}" class="no-dec">
                            <p class="text-center">
                                <img src="{{ asset('assets/img/teacher/ass14.png') }}" class="icon-image">
                            </p>
                            <p class="text-center text-dark mt-adjust">校内监控</p>
                        </a>
                    </div>

                </div>

                <div class="clearfix"></div>
                <h3 class="title">室内管理</h3>
                <div class="row">
                    <div class="col-4">
                        <p class="text-center">
                            <img src="{{ asset('assets/img/teacher/ass15.png') }}" class="icon-image">
                        </p>
                        <p class="text-center text-dark mt-adjust">教室管理</p>
                    </div>
                    <div class="col-4">
                        <p class="text-center">
                            <img src="{{ asset('assets/img/teacher/ass16.png') }}" class="icon-image">
                        </p>
                        <p class="text-center text-dark mt-adjust">会议室管理</p>
                    </div>
                </div>
                <div class="clearfix"></div>

                <h3 class="title">设备管理</h3>
                <div class="row">
                    <div class="col-4">
                        <a class="no-dec" href="#">
                        <p class="text-center">
                            <img src="{{ asset('assets/img/teacher/ass17.png') }}" class="icon-image">
                        </p>
                        <p class="text-center text-dark mt-adjust">室内设备</p>
                        </a>
                    </div>
                    <div class="col-4">
                        <a class="no-dec" href="#">
                        <p class="text-center">
                            <img src="{{ asset('assets/img/teacher/ass18.png') }}" class="icon-image">
                        </p>
                        <p class="text-center text-dark mt-adjust">室外设备</p>
                        </a>
                    </div>
                </div>
                <div class="clearfix"></div>

                <h3 class="title">后勤管理</h3>
                <div class="row">
                    <div class="col-4">
                        <a class="no-dec" href="#">
                            <p class="text-center">
                                <img src="{{ asset('assets/img/teacher/ass19.png') }}" class="icon-image">
                            </p>
                            <p class="text-center text-dark mt-adjust">食堂</p>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
