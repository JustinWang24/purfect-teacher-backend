<?php
/**
 * @var \App\User $teacher
 */
$teacher = \Illuminate\Support\Facades\Auth::user();
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
                            <img src="{{ \Illuminate\Support\Facades\Auth::user()->profile->avatar }}" class="img-circle user-img-circle"
                                 alt="User Image" />
                        </div>
                        <div class="pull-left info">
                            <p> {{ \Illuminate\Support\Facades\Auth::user()->name }}</p>
                        </div>
                    </div>
                </li>

                <li class="nav-item start">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="material-icons">home</i>
                        <span class="title">首页</span>
                        <span class="arrow nav-toggle"></span>
                    </a>
                    <ul class="sub-menu">
                        @include('layouts.desktop.elements.submenu_selected',['routeName'=>'teacher.ly.home.message-center','name'=>'消息中心'])
                        @include('layouts.desktop.elements.submenu_selected',['routeName'=>'teacher.ly.home.school-news','name'=>'校园新闻'])
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('teacher.ly.oa.index') }}" class="nav-link">
                        <i class="material-icons">dvr</i>
                        <span class="title">办公</span>
                        <span class="arrow nav-toggle"></span>
                    </a>
                    <ul class="sub-menu">
                        @include('layouts.desktop.elements.submenu_selected',['routeName'=>'teacher.ly.oa.notices-center','name'=>'通知/公告/检查'])
                        @include('layouts.desktop.elements.submenu_selected',['routeName'=>'teacher.ly.oa.logs','name'=>'日志'])
                        @include('layouts.desktop.elements.submenu_selected',['routeName'=>'teacher.ly.oa.internal-messages','name'=>'内部信'])
                        @include('layouts.desktop.elements.submenu_selected',['routeName'=>'teacher.ly.oa.meetings','name'=>'会议'])
                        @include('layouts.desktop.elements.submenu_selected',['routeName'=>'teacher.ly.oa.tasks','name'=>'任务'])
                        @include('layouts.desktop.elements.submenu_selected',['routeName'=>'teacher.ly.oa.applications','name'=>'申请'])
                        @include('layouts.desktop.elements.submenu_selected',['routeName'=>'teacher.ly.oa.approvals','name'=>'审批'])
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('teacher.ly.assistant.index') }}" class="nav-link">
                        <i class="material-icons">local_library</i>
                        <span class="title">助手</span>
                        <span class="arrow nav-toggle"></span>
                    </a>
                    <ul class="sub-menu">
                        @include('layouts.desktop.elements.submenu_selected',['routeName'=>'teacher.ly.assistant.check-in','name'=>'签到'])
                        @include('layouts.desktop.elements.submenu_selected',['routeName'=>'teacher.ly.assistant.evaluation','name'=>'评分'])
                        @include('layouts.desktop.elements.submenu_selected',['routeName'=>'teacher.ly.assistant.electives','name'=>'选课'])
                        @include('layouts.desktop.elements.submenu_selected',['routeName'=>'teacher.ly.assistant.grades-manager','name'=>'班级管理'])
                        @include('layouts.desktop.elements.submenu_selected',['routeName'=>'teacher.ly.assistant.students-manager','name'=>'学生信息'])
                        @include('layouts.desktop.elements.submenu_selected',['routeName'=>'teacher.ly.assistant.grades-check-in','name'=>'班级签到'])
                        @include('layouts.desktop.elements.submenu_selected',['routeName'=>'teacher.ly.assistant.grades-evaluations','name'=>'班级评分'])
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="material-icons">map</i>
                        <span class="title">教学管理</span>
                        <span class="arrow nav-toggle"></span>
                    </a>
                    <ul class="sub-menu">
                        @foreach($teacher->myCourses as $uc)
                        @include('layouts.desktop.elements.submenu_selected',['routeName'=>'teacher.course.materials.manager','routeParams'=>['teacher'=>$teacher->id,'course_id'=>$uc->course->id],'name'=>$uc->course->name])
                        @endforeach
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link nav-toggle">
                        <i class="material-icons">store</i>
                        <span class="title">考试管理</span>
                        <span class="selected"></span>
                        <span class="arrow"></span>
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
                        {{--<a href="javascript:void(0);" class="nav-link nav-toggle">
                            <i class="material-icons">event_seat</i>
                            <span class="title">会议管理</span>
                            <span class="selected"></span>
                            <span class="arrow open"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item active">
                                <a href="" class="nav-link ">
                                    <span class="title">分组列表</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                        </ul>--}}
                        {{--<a href="{{ route('teacher.conference.index',['uuid'=>session('school.uuid')])  }}" class="nav-link">
                            <i class="material-icons">event_seat</i>
                            <span class="title">会议管理</span>
                        </a>--}}
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

