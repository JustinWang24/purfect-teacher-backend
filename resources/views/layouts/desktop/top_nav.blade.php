<div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner ">
        <!-- logo start -->
        <div class="page-logo">
            <a href="{{ route('home') }}">
                <span class="logo-icon material-icons fa-rotate-45">school</span>
                <span class="logo-default">{{ env('APP_NAME') }}</span> </a>
        </div>
        <!-- logo end -->
        <ul class="nav navbar-nav navbar-left in">
            <li><a href="#" class="menu-toggler sidebar-toggler"><i class="icon-menu"></i></a></li>
        </ul>
        <form class="search-form-opened" action="#" method="GET" style="display: none;">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search..." name="query">
                <span class="input-group-btn">
							<a href="javascript:;" class="btn submit">
								<i class="icon-magnifier"></i>
							</a>
						</span>
            </div>
        </form>
        <!-- start mobile menu -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
           data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- end mobile menu -->
        <!-- start header menu -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!-- start language menu -->
                <li><a href="javascript:;" class="fullscreen-btn"><i class="fa fa-arrows-alt"></i></a></li>

                <li id="file-manager-app">
                    <a class="dropdown-toggle" v-on:click="showFileManager">
                        <i class="fa fa-database"></i>&nbsp;我的云盘
                    </a>
                    @include('reusable_elements.section.file_manager_component')
                </li>

                <!-- start notification dropdown -->
                <li class="dropdown dropdown-extended dropdown-notification" id="notification-app-data-top" data-schoolid="{{ session('school.id') }}">
                    <div id="{{ env('APP_DEBUG', true) ? null : 'header_notification_bar' }}" style="padding-top: 13px;">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                           data-close-others="true">
                            <i class="fa fa-bell-o"></i>
                            <span style="margin-top: -4px;" class="badge headerBadgeColor1" v-if="hasNew"> 新 </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="external">
                                <h3><span class="bold">通知消息</span></h3>
                                <span class="notification-label purple-bgcolor">最近@{{ pageSize > total ? total : pageSize }}条</span>
                            </li>
                            <li>
                                <ul class="dropdown-menu-list small-slimscroll-style" data-handle-color="#637283">
                                    <notification-item v-for="(msg, idx) in messages" :key="idx" :message="msg"></notification-item>
                                </ul>
                                <div class="dropdown-menu-footer">
                                    <a href="#"> 所有消息 </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                       data-close-others="true">
                        <img alt="" class="img-circle " src="{{ asset('assets/img/dp.jpg') }}" />
                        <span class="username username-hide-on-mobile"> {{ Auth::user()->name }} </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="#">
                                <i class="icon-user"></i> 我的个人资料 </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="icon-directions"></i> 用户使用手册
                            </a>
                        </li>
                        <li class="divider"> </li>
                        <li>
                            <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="icon-logout"></i> 退出系统 </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>