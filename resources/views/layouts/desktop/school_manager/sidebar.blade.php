<div class="sidebar-container">
    <div class="sidemenu-container navbar-collapse collapse fixed-menu">
        <div id="remove-scroll" class="left-sidemenu">
            <ul class="sidemenu  page-header-fixed sidemenu-hover-submenu" data-keep-expanded="false"
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
                            <p> Kiran Patel</p>
                            <a href="#"><i class="fa fa-circle user-online"></i><span class="txtOnline">
												Online</span></a>
                        </div>
                    </div>
                </li>

                <!--begin-->

                <li class="nav-item">
                    <a href="{{ route('school_manager.scenery.list') }}" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">校园门户管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link nav-toggle">
                        <i class="material-icons">school</i>
                        <span class="title">课程</span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('school_manager.courses.manager',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                                <span class="title">课程管理</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('teacher.textbook.manager') }}" class="nav-link ">
                                <span class="title">教材管理</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('school_manager.timetable.manager.preview',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                                <span class="title">课程表管理</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">会议管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="{{ route('teacher.conference.index') }}" class="nav-link ">
                                <span class="title">会议列表</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
