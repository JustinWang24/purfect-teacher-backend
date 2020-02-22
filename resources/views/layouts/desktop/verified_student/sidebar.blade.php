<?php
/**
 * @var \App\User $student
 */
$student = \Illuminate\Support\Facades\Auth::user();
$myCourses = $student->myCourses();
?>
<div class="sidebar-container">
    <div class="sidemenu-container navbar-collapse collapse fixed-menu">
        <div id="remove-scroll" class="left-sidemenu">
            <ul class="sidemenu  page-header-fixed sidemenu-closed" data-keep-expanded="false"
                data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                <li class="sidebar-toggler-wrapper hide">
                    <div class="sidebar-toggler">
                        <span></span>
                    </div>
                </li>
                <li class="sidebar-user-panel">
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="{{ $student->profile->avatar }}" class="img-circle user-img-circle"
                                 alt="User Image" />
                        </div>
                        <div class="pull-left info">
                            <p> {{ $student->name }}</p>
                        </div>
                    </div>
                </li>

                <li class="nav-item start">
                    <a href="{{ route('home') }}" class="nav-link nav-toggle">
                        <i class="material-icons">home</i>
                        <span class="title">首页</span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="{{ route('teacher.scenery.index') }}" class="nav-link ">
                                <span class="title">校园相册</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{ route('teacher.scenery.profile') }}" class="nav-link ">
                                <span class="title">学校简介</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="material-icons">map</i>
                        <span class="title">我的课程</span>
                        <span class="arrow nav-toggle"></span>
                    </a>
                    <ul class="sub-menu">
                        @foreach($myCourses as $myCourse)
                            @include('layouts.desktop.elements.submenu_selected',['routeName'=>'verified_student.course.manager','routeParams'=>['student'=>$student->id,'course_id'=>$myCourse->id],'name'=>$myCourse->name])
                        @endforeach
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link nav-toggle">
                        <i class="material-icons">store</i>
                        <span class="title">考试管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="{{ route('teacher.exam.index') }}" class="nav-link ">
                                <span class="title">考试列表</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                    </ul>
                </li>

                @if(session('school.id'))
                    <li class="nav-item">
                        <a href="{{ route('teacher.conference.index',['uuid'=>session('school.uuid')])  }}" class="nav-link">
                            <i class="material-icons">event_seat</i>
                            <span class="title">会议管理</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="javascript:void(0);" class="nav-link nav-toggle">
                            <i class="material-icons">people</i>
                            <span class="title">招生管理</span>
                            <span class="selected"></span>
                            <span class="arrow open"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('teacher.planRecruit.list') }}" class="nav-link ">
                                    <span class="title">预招管理</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('school_manager.consult.list') }}" class="nav-link ">
                                    <span class="title">咨询管理</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('school_manager.facility.list') }}" class="nav-link">
                            <i class="material-icons">dashboard</i>
                            <span class="title">设备管理</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>

