<?php
/**
 * @var \App\User $teacher
 */
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
                        <li class="nav-item active">
                            <a href="{{ route('teacher.ly.home.message-center') }}" class="nav-link">
                                <span class="title">消息中心</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{ route('teacher.ly.home.school-news') }}" class="nav-link">
                                <span class="title">校园新闻</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('teacher.ly.oa.index') }}" class="nav-link">
                        <i class="material-icons">home</i>
                        <span class="title">办公</span>
                        <span class="arrow nav-toggle"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('teacher.ly.oa.notices-center') }}" class="nav-link ">
                                <span class="title">通知/公告/检查</span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{ route('teacher.ly.oa.logs') }}" class="nav-link ">
                                <span class="title">日志</span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{ route('teacher.ly.oa.internal-messages') }}" class="nav-link ">
                                <span class="title">内部信</span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{ route('teacher.ly.oa.meetings') }}" class="nav-link ">
                                <span class="title">会议</span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{ route('teacher.ly.oa.tasks') }}" class="nav-link ">
                                <span class="title">任务</span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{ route('teacher.ly.oa.applications') }}" class="nav-link ">
                                <span class="title">申请</span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{ route('teacher.ly.oa.approvals') }}" class="nav-link ">
                                <span class="title">审批</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('teacher.ly.assistant.index') }}" class="nav-link">
                        <i class="material-icons">home</i>
                        <span class="title">助手</span>
                        <span class="arrow nav-toggle"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('teacher.ly.assistant.check-in') }}" class="nav-link ">
                                <span class="title">签到</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('teacher.ly.assistant.evaluation') }}" class="nav-link ">
                                <span class="title">评分</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('teacher.textbook.manager') }}" class="nav-link">
                        <i class="material-icons">map</i>
                        <span class="title">教材管理</span>
                    </a>
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

