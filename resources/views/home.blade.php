@extends('layouts.app')

@section('content')
    <!-- start header -->
    <div class="page-header navbar navbar-fixed-top">
        <div class="page-header-inner ">
            <!-- logo start -->
            <div class="page-logo">
                <a href="index.html">
                    <span class="logo-icon material-icons fa-rotate-45">school</span>
                    <span class="logo-default">Smart</span> </a>
            </div>
            <!-- logo end -->
            <ul class="nav navbar-nav navbar-left in">
                <li><a href="#" class="menu-toggler sidebar-toggler"><i class="icon-menu"></i></a></li>
            </ul>
            <form class="search-form-opened" action="#" method="GET">
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
                    <li class="dropdown language-switch">
                        <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <img
                                    src="../assets/img/flags/gb.png" class="position-left" alt=""> English <span
                                    class="fa fa-angle-down"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="deutsch"><img src="../assets/img/flags/de.png" alt=""> Deutsch</a>
                            </li>
                            <li>
                                <a class="ukrainian"><img src="../assets/img/flags/ua.png" alt=""> Українська</a>
                            </li>
                            <li>
                                <a class="english"><img src="../assets/img/flags/gb.png" alt=""> English</a>
                            </li>
                            <li>
                                <a class="espana"><img src="../assets/img/flags/es.png" alt=""> España</a>
                            </li>
                            <li>
                                <a class="russian"><img src="../assets/img/flags/ru.png" alt=""> Русский</a>
                            </li>
                        </ul>
                    </li>
                    <!-- end language menu -->
                    <!-- start notification dropdown -->
                    <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                           data-close-others="true">
                            <i class="fa fa-bell-o"></i>
                            <span class="badge headerBadgeColor1"> 6 </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="external">
                                <h3><span class="bold">Notifications</span></h3>
                                <span class="notification-label purple-bgcolor">New 6</span>
                            </li>
                            <li>
                                <ul class="dropdown-menu-list small-slimscroll-style" data-handle-color="#637283">
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">just now</span>
                                            <span class="details">
													<span class="notification-icon circle deepPink-bgcolor"><i
                                                                class="fa fa-check"></i></span>
													Congratulations!. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">3 mins</span>
                                            <span class="details">
													<span class="notification-icon circle purple-bgcolor"><i
                                                                class="fa fa-user o"></i></span>
													<b>John Micle </b>is now following you. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">7 mins</span>
                                            <span class="details">
													<span class="notification-icon circle blue-bgcolor"><i
                                                                class="fa fa-comments-o"></i></span>
													<b>Sneha Jogi </b>sent you a message. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">12 mins</span>
                                            <span class="details">
													<span class="notification-icon circle pink"><i
                                                                class="fa fa-heart"></i></span>
													<b>Ravi Patel </b>like your photo. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">15 mins</span>
                                            <span class="details">
													<span class="notification-icon circle yellow"><i
                                                                class="fa fa-warning"></i></span> Warning! </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">10 hrs</span>
                                            <span class="details">
													<span class="notification-icon circle red"><i
                                                                class="fa fa-times"></i></span> Application error. </span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="dropdown-menu-footer">
                                    <a href="javascript:void(0)"> All notifications </a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- end notification dropdown -->
                    <!-- start message dropdown -->
                    <li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                           data-close-others="true">
                            <i class="fa fa-envelope-o"></i>
                            <span class="badge headerBadgeColor2"> 2 </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="external">
                                <h3><span class="bold">Messages</span></h3>
                                <span class="notification-label cyan-bgcolor">New 2</span>
                            </li>
                            <li>
                                <ul class="dropdown-menu-list small-slimscroll-style" data-handle-color="#637283">
                                    <li>
                                        <a href="#">
												<span class="photo">
													<img src="../assets/img/prof/prof2.jpg" class="img-circle" alt="">
												</span>
                                            <span class="subject">
													<span class="from"> Sarah Smith </span>
													<span class="time">Just Now </span>
												</span>
                                            <span class="message"> Jatin I found you on LinkedIn... </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
												<span class="photo">
													<img src="../assets/img/prof/prof3.jpg" class="img-circle" alt="">
												</span>
                                            <span class="subject">
													<span class="from"> John Deo </span>
													<span class="time">16 mins </span>
												</span>
                                            <span class="message"> Fwd: Important Notice Regarding Your Domain
													Name... </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
												<span class="photo">
													<img src="../assets/img/prof/prof1.jpg" class="img-circle" alt="">
												</span>
                                            <span class="subject">
													<span class="from"> Rajesh </span>
													<span class="time">2 hrs </span>
												</span>
                                            <span class="message"> pls take a print of attachments. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
												<span class="photo">
													<img src="../assets/img/prof/prof8.jpg" class="img-circle" alt="">
												</span>
                                            <span class="subject">
													<span class="from"> Lina Smith </span>
													<span class="time">40 mins </span>
												</span>
                                            <span class="message"> Apply for Ortho Surgeon </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
												<span class="photo">
													<img src="../assets/img/prof/prof5.jpg" class="img-circle" alt="">
												</span>
                                            <span class="subject">
													<span class="from"> Jacob Ryan </span>
													<span class="time">46 mins </span>
												</span>
                                            <span class="message"> Request for leave application. </span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="dropdown-menu-footer">
                                    <a href="#"> All Messages </a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- end message dropdown -->
                    <!-- start manage user dropdown -->
                    <li class="dropdown dropdown-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                           data-close-others="true">
                            <img alt="" class="img-circle " src="../assets/img/dp.jpg" />
                            <span class="username username-hide-on-mobile"> Kiran </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="user_profile.html">
                                    <i class="icon-user"></i> Profile </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="icon-settings"></i> Settings
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="icon-directions"></i> Help
                                </a>
                            </li>
                            <li class="divider"> </li>
                            <li>
                                <a href="lock_screen.html">
                                    <i class="icon-lock"></i> Lock
                                </a>
                            </li>
                            <li>
                                <a href="login.html">
                                    <i class="icon-logout"></i> Log Out </a>
                            </li>
                        </ul>
                    </li>
                    <!-- end manage user dropdown -->
                    <li class="dropdown dropdown-quick-sidebar-toggler">
                        <a id="headerSettingButton" class="mdl-button mdl-js-button mdl-button--icon pull-right"
                           data-upgraded=",MaterialButton">
                            <i class="material-icons">more_vert</i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- end header -->
    <!-- start page container -->
    <div class="page-container">
        <!-- start sidebar menu -->
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
                                    <img src="../assets/img/dp.jpg" class="img-circle user-img-circle"
                                         alt="User Image" />
                                </div>
                                <div class="pull-left info">
                                    <p> Kiran Patel</p>
                                    <a href="#"><i class="fa fa-circle user-online"></i><span class="txtOnline">
												Online</span></a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item start active open">
                            <a href="#" class="nav-link nav-toggle">
                                <i class="material-icons">dashboard</i>
                                <span class="title">Dashboard</span>
                                <span class="selected"></span>
                                <span class="arrow open"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item active">
                                    <a href="index.html" class="nav-link ">
                                        <span class="title">Dashboard 1</span>
                                        <span class="selected"></span>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a href="dashboard2.html" class="nav-link ">
                                        <span class="title">Dashboard 2</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="dashboard3.html" class="nav-link ">
                                        <span class="title">Dashboard 3</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="event.html" class="nav-link nav-toggle"> <i class="material-icons">event</i>
                                <span class="title">Event Management</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link nav-toggle"> <i class="material-icons">person</i>
                                <span class="title">Professors</span> <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="all_professors.html" class="nav-link "> <span class="title">All
												Professors</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_professor.html" class="nav-link "> <span class="title">Add
												Professor</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_professor_bootstrap.html" class="nav-link "> <span
                                                class="title">Add Professor Bootstrap</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="edit_professor.html" class="nav-link "> <span class="title">Edit
												Professor</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="professor_profile.html" class="nav-link "> <span class="title">About
												Professor</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link nav-toggle"><i class="material-icons">group</i>
                                <span class="title">Students</span><span class="arrow"></span></a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="all_students.html" class="nav-link "> <span class="title">All
												Students</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_student.html" class="nav-link "> <span class="title">Add
												Student</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_student_bootstrap.html" class="nav-link "> <span class="title">Add
												Student Bootstrap</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="edit_student.html" class="nav-link "> <span class="title">Edit
												Student</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="student_profile.html" class="nav-link "> <span class="title">About
												Student</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link nav-toggle"> <i class="material-icons">school</i>
                                <span class="title">Courses</span> <span class="arrow"></span>
                                <span class="label label-rouded label-menu label-success">new</span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="all_courses.html" class="nav-link "> <span class="title">All
												Courses</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_course.html" class="nav-link "> <span class="title">Add
												Course</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_course_bootstrap.html" class="nav-link "> <span class="title">Add
												Course Bootstrap</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="edit_course.html" class="nav-link "> <span class="title">Edit
												Course</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="course_details.html" class="nav-link "> <span class="title">About
												Course</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link nav-toggle"> <i class="material-icons">local_library</i>
                                <span class="title">Library</span> <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="all_assets.html" class="nav-link "> <span class="title">All Library
												Assets</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_library.html" class="nav-link "> <span class="title">Add Library
												Asset</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_library_bootstrap.html" class="nav-link "> <span class="title">Add
												Asset Bootstrap</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="edit_library.html" class="nav-link "> <span class="title">Edit
												Asset</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link nav-toggle"> <i class="material-icons">business</i>
                                <span class="title">Departments</span> <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="all_department.html" class="nav-link "> <span class="title">All
												Departments</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_department.html" class="nav-link "> <span class="title">Add
												Department</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_department_bootstrap.html" class="nav-link "> <span
                                                class="title">Add Department Bootstrap</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="edit_department.html" class="nav-link "> <span class="title">Edit
												Department</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link nav-toggle"> <i class="material-icons">face</i>
                                <span class="title">Staff</span> <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="all_staffs.html" class="nav-link "> <span class="title">All
												Staff</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_staff.html" class="nav-link "> <span class="title">Add Staff</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_staff_bootstrap.html" class="nav-link "> <span class="title">Add
												Staff Bootstrap</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="edit_staff.html" class="nav-link "> <span class="title">Edit
												Staff</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="staff_profile.html" class="nav-link "> <span class="title">Staff
												Profile</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link nav-toggle"> <i
                                        class="material-icons">airline_seat_individual_suite</i>
                                <span class="title">Holiday</span> <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="all_holidays.html" class="nav-link "> <span class="title">All
												Holiday</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_holiday.html" class="nav-link "> <span class="title">Add
												Holiday</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_holiday_bootstrap.html" class="nav-link "> <span class="title">Add
												Holiday Bootstrap</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="edit_holiday.html" class="nav-link "> <span class="title">Edit
												Holiday</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="holiday_calendar.html" class="nav-link "> <span class="title">Holiday
												Calendar</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link nav-toggle">
                                <i class="material-icons">email</i>
                                <span class="title">Email</span>
                                <span class="arrow"></span>
                                <span class="label label-rouded label-menu label-danger">new</span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="email_inbox.html" class="nav-link ">
                                        <span class="title">Inbox</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="email_view.html" class="nav-link ">
                                        <span class="title">View Mail</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="email_compose.html" class="nav-link ">
                                        <span class="title">Compose Mail</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link nav-toggle"> <i class="material-icons">monetization_on</i>
                                <span class="title">Fees</span> <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="fees_collection.html" class="nav-link "> <span class="title">Fees
												Collection</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_fees.html" class="nav-link "> <span class="title">Add Fees </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_fees_bootstrap.html" class="nav-link "> <span class="title">Add
												Fees Bootstrap</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="fees_receipt.html" class="nav-link "> <span class="title">Fee
												Receipt</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="widget.html" class="nav-link nav-toggle"> <i class="material-icons">widgets</i>
                                <span class="title">Widget</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link nav-toggle">
                                <i class="material-icons">dvr</i>
                                <span class="title">UI Elements</span>
                                <span class="label label-rouded label-menu label-warning">new</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="ui_buttons.html" class="nav-link ">
                                        <span class="title">Buttons</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="ui_tabs_accordions_navs.html" class="nav-link ">
                                        <span class="title">Tabs &amp; Accordions</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="ui_typography.html" class="nav-link ">
                                        <span class="title">Typography</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="notification.html" class="nav-link ">
                                        <span class="title">Notification</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="ui_icons.html" class="nav-link ">
                                        <span class="title">Icons</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="ui_panels.html" class="nav-link ">
                                        <span class="title">Panels</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="ui_grid.html" class="nav-link ">
                                        <span class="title">Grids</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="calendar.html" class="nav-link ">
                                        <span class="title">Calender</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="ui_tree.html" class="nav-link ">
                                        <span class="title">Tree View</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="ui_carousel.html" class="nav-link ">
                                        <span class="title">Carousel</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link nav-toggle">
                                <i class="material-icons">store</i>
                                <span class="title">Material Elements</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="material_button.html" class="nav-link ">
                                        <span class="title">Buttons</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="material_tab.html" class="nav-link ">
                                        <span class="title">Tabs</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="material_chips.html" class="nav-link ">
                                        <span class="title">Chips</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="material_grid.html" class="nav-link ">
                                        <span class="title">Grid</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="material_icons.html" class="nav-link ">
                                        <span class="title">Icon</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="material_form.html" class="nav-link ">
                                        <span class="title">Form</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="material_datepicker.html" class="nav-link ">
                                        <span class="title">DatePicker</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="material_select.html" class="nav-link ">
                                        <span class="title">Select Item</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="material_loading.html" class="nav-link ">
                                        <span class="title">Loading</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="material_menu.html" class="nav-link ">
                                        <span class="title">Menu</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="material_slider.html" class="nav-link ">
                                        <span class="title">Slider</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="material_tables.html" class="nav-link ">
                                        <span class="title">Tables</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="material_toggle.html" class="nav-link ">
                                        <span class="title">Toggle</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="material_badges.html" class="nav-link ">
                                        <span class="title">Badges</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="material-icons">subtitles</i>
                                <span class="title">Forms </span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="layouts_form.html" class="nav-link ">
                                        <span class="title">Form Layout</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="advance_form.html" class="nav-link ">
                                        <span class="title">Advance Component</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="wizard.html" class="nav-link ">
                                        <span class="title">Form Wizard</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="validation_form.html" class="nav-link ">
                                        <span class="title">Form Validation</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="editable_form.html" class="nav-link ">
                                        <span class="title">Editor</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="material-icons">list</i>
                                <span class="title">Data Tables</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="basic_table.html" class="nav-link ">
                                        <span class="title">Basic Tables</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="advanced_table.html" class="nav-link ">
                                        <span class="title">Advance Tables</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="export_table.html" class="nav-link ">
                                        <span class="title">Export Tables</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="child_row_table.html" class="nav-link ">
                                        <span class="title">Child Row Tables</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="group_table.html" class="nav-link ">
                                        <span class="title">Grouping</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="tableData.html" class="nav-link ">
                                        <span class="title">Tables With Sourced Data</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="material-icons">timeline</i>
                                <span class="title">Charts</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="charts_echarts.html" class="nav-link ">
                                        <span class="title">eCharts</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="charts_morris.html" class="nav-link ">
                                        <span class="title">Morris Charts</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="charts_chartjs.html" class="nav-link ">
                                        <span class="title">Chartjs</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="material-icons">map</i>
                                <span class="title">Maps</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="google_maps.html" class="nav-link ">
                                        <span class="title">Google Maps</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="vector_maps.html" class="nav-link ">
                                        <span class="title">Vector Maps</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:;" class="nav-link nav-toggle"> <i
                                        class="material-icons">description</i>
                                <span class="title">Extra pages</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item  ">
                                    <a href="login.html" class="nav-link "> <span class="title">Login</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="sign_up.html" class="nav-link "> <span class="title">Sign Up</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="forgot_password.html" class="nav-link "> <span class="title">Forgot
												Password</span>
                                    </a>
                                </li>
                                <li class="nav-item"><a href="user_profile.html" class="nav-link "><span
                                                class="title">Profile</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="contact.html" class="nav-link "> <span class="title">Contact Us</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="lock_screen.html" class="nav-link "> <span class="title">Lock
												Screen</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="page-404.html" class="nav-link "> <span class="title">404 Page</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="page-500.html" class="nav-link "> <span class="title">500 Page</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="blank_page.html" class="nav-link "> <span class="title">Blank
												Page</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="material-icons">slideshow</i>
                                <span class="title">Multi Level Menu</span>
                                <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="javascript:;" class="nav-link nav-toggle">
                                        <i class="fa fa-university"></i> Item 1
                                        <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="nav-item">
                                            <a href="javascript:;" class="nav-link nav-toggle">
                                                <i class="fa fa-bell-o"></i> Arrow Toggle
                                                <span class="arrow "></span>
                                            </a>
                                            <ul class="sub-menu">
                                                <li class="nav-item">
                                                    <a href="javascript:;" class="nav-link">
                                                        <i class="fa fa-calculator"></i> Sample Link 1</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#" class="nav-link">
                                                        <i class="fa fa-clone"></i> Sample Link 2</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#" class="nav-link">
                                                        <i class="fa fa-cogs"></i> Sample Link 3</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="fa fa-file-pdf-o"></i> Sample Link 1</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="fa fa-rss"></i> Sample Link 2</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="fa fa-hdd-o"></i> Sample Link 3</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:;" class="nav-link nav-toggle">
                                        <i class="fa fa-gavel"></i> Arrow Toggle
                                        <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="fa fa-paper-plane"></i> Sample Link 1</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="fa fa-power-off"></i> Sample Link 1</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="fa fa-recycle"></i> Sample Link 1
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-volume-up"></i> Item 3 </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- end sidebar menu -->
        <!-- start page content -->
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="page-bar">
                    <div class="page-title-breadcrumb">
                        <div class=" pull-left">
                            <div class="page-title">Dashboard</div>
                        </div>
                        <ol class="breadcrumb page-breadcrumb pull-right">
                            <li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
                                                                   href="index.html">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
                            </li>
                            <li class="active">Dashboard</li>
                        </ol>
                    </div>
                </div>
                <!-- start widget -->
                <div class="state-overview">
                    <div class="row">
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="info-box bg-b-green">
                                <span class="info-box-icon push-bottom"><i class="material-icons">group</i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Students</span>
                                    <span class="info-box-number">450</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 45%"></div>
                                    </div>
                                    <span class="progress-description">
											45% Increase in 28 Days
										</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="info-box bg-b-yellow">
                                <span class="info-box-icon push-bottom"><i class="material-icons">person</i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">New Students</span>
                                    <span class="info-box-number">155</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 40%"></div>
                                    </div>
                                    <span class="progress-description">
											40% Increase in 28 Days
										</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="info-box bg-b-blue">
                                <span class="info-box-icon push-bottom"><i class="material-icons">school</i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Course</span>
                                    <span class="info-box-number">52</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 85%"></div>
                                    </div>
                                    <span class="progress-description">
											85% Increase in 28 Days
										</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="info-box bg-b-pink">
									<span class="info-box-icon push-bottom"><i
                                                class="material-icons">monetization_on</i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Fees Collection</span>
                                    <span class="info-box-number">13,921</span><span>$</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 50%"></div>
                                    </div>
                                    <span class="progress-description">
											50% Increase in 28 Days
										</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                    </div>
                </div>
                <!-- end widget -->
                <!-- chart start -->
                <div class="row">
                    <div class="col-sm-8">
                        <div class="card card-box">
                            <div class="card-head">
                                <header>University Survey</header>
                                <div class="tools">
                                    <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
                                    <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
                                    <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                                </div>
                            </div>
                            <div class="card-body no-padding height-9">
                                <div class="row">
                                    <canvas id="canvas1"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card card-box">
                            <div class="card-head">
                                <header>University Survey</header>
                                <div class="tools">
                                    <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
                                    <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
                                    <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                                </div>
                            </div>
                            <div class="card-body no-padding height-9">
                                <div class="row">
                                    <canvas id="chartjs_pie"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Chart end -->
                <!-- start course list -->
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-12 col-sm-6">
                        <div class="blogThumb">
                            <div class="thumb-center"><img class="img-responsive" alt="user"
                                                           src="../assets/img/course/course1.jpg"></div>
                            <div class="course-box">
                                <h4>PHP Development Course</h4>
                                <div class="text-muted"><span class="m-r-10">April 23</span>
                                    <a class="course-likes m-l-10" href="#"><i class="fa fa-heart-o"></i> 654</a>
                                </div>
                                <p><span><i class="ti-alarm-clock"></i> Duration: 6 Months</span></p>
                                <p><span><i class="ti-user"></i> Professor: Jane Doe</span></p>
                                <p><span><i class="fa fa-graduation-cap"></i> Students: 200+</span></p>
                                <button type="button"
                                        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-info">Read
                                    More</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 col-sm-6">
                        <div class="blogThumb">
                            <div class="thumb-center"><img class="img-responsive" alt="user"
                                                           src="../assets/img/course/course2.jpg"></div>
                            <div class="course-box">
                                <h4>PHP Development Course</h4>
                                <div class="text-muted"><span class="m-r-10">April 23</span>
                                    <a class="course-likes m-l-10" href="#"><i class="fa fa-heart-o"></i> 654</a>
                                </div>
                                <p><span><i class="ti-alarm-clock"></i> Duration: 6 Months</span></p>
                                <p><span><i class="ti-user"></i> Professor: Jane Doe</span></p>
                                <p><span><i class="fa fa-graduation-cap"></i> Students: 200+</span></p>
                                <button type="button"
                                        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-info">Read
                                    More</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 col-sm-6">
                        <div class="blogThumb">
                            <div class="thumb-center"><img class="img-responsive" alt="user"
                                                           src="../assets/img/course/course3.jpg"></div>
                            <div class="course-box">
                                <h4>PHP Development Course</h4>
                                <div class="text-muted"><span class="m-r-10">April 23</span>
                                    <a class="course-likes m-l-10" href="#"><i class="fa fa-heart-o"></i> 654</a>
                                </div>
                                <p><span><i class="ti-alarm-clock"></i> Duration: 6 Months</span></p>
                                <p><span><i class="ti-user"></i> Professor: Jane Doe</span></p>
                                <p><span><i class="fa fa-graduation-cap"></i> Students: 200+</span></p>
                                <button type="button"
                                        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-info">Read
                                    More</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 col-sm-6">
                        <div class="blogThumb">
                            <div class="thumb-center"><img class="img-responsive" alt="user"
                                                           src="../assets/img/course/course4.jpg"></div>
                            <div class="course-box">
                                <h4>PHP Development Course</h4>
                                <div class="text-muted"><span class="m-r-10">April 23</span>
                                    <a class="course-likes m-l-10" href="#"><i class="fa fa-heart-o"></i> 654</a>
                                </div>
                                <p><span><i class="ti-alarm-clock"></i> Duration: 6 Months</span></p>
                                <p><span><i class="ti-user"></i> Professor: Jane Doe</span></p>
                                <p><span><i class="fa fa-graduation-cap"></i> Students: 200+</span></p>
                                <button type="button"
                                        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-info">Read
                                    More</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End course list -->
                <div class="row">
                    <!-- Quick Mail start -->
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="card-box">
                            <div class="card-head">
                                <header>Quick Mail</header>
                                <button id="demo_menu-lower-right"
                                        class="mdl-button mdl-js-button mdl-button--icon pull-right"
                                        data-upgraded=",MaterialButton">
                                    <i class="material-icons">more_vert</i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
                                    data-mdl-for="demo_menu-lower-right">
                                    <li class="mdl-menu__item"><i class="material-icons">assistant_photo</i>Action
                                    </li>
                                    <li class="mdl-menu__item"><i class="material-icons">print</i>Another action
                                    </li>
                                    <li class="mdl-menu__item"><i class="material-icons">favorite</i>Something else
                                        here</li>
                                </ul>
                            </div>
                            <div class="card-body ">
                                <div class="mail-list">
                                    <div class="compose-mail">
                                        <form method="post">
                                            <div class="form-group">
                                                <label for="to" class="">To:</label>
                                                <input type="text" tabindex="1" id="to" class="form-control">
                                                <div class="compose-options">
                                                    <a onclick="$(this).hide(); $('#cc').parent().removeClass('hidden'); $('#cc').focus();"
                                                       href="javascript:;">Cc</a>
                                                    <a onclick="$(this).hide(); $('#bcc').parent().removeClass('hidden'); $('#bcc').focus();"
                                                       href="javascript:;">Bcc</a>
                                                </div>
                                            </div>
                                            <div class="form-group hidden">
                                                <label for="cc" class="">Cc:</label>
                                                <input type="text" tabindex="2" id="cc" class="form-control">
                                            </div>
                                            <div class="form-group hidden">
                                                <label for="bcc" class="">Bcc:</label>
                                                <input type="text" tabindex="2" id="bcc" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="subject" class="">Subject:</label>
                                                <input type="text" tabindex="1" id="subject" class="form-control">
                                            </div>
                                            <div>
                                                <div id="summernote"></div>
                                                <input type="file" class="default" multiple>
                                            </div>
                                            <!--   <div class="btn-group margin-top-20 ">
                                                <button class="btn btn-primary btn-sm margin-right-10"><i class="fa fa-check"></i> Send</button>
                                               </div> -->
                                            <div class="box-footer clearfix">
                                                <button type="button"
                                                        class="mdl-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-primary pull-right">Send
                                                    <i class="fa fa-paper-plane-o"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Quick Mail end -->
                    <!-- Activity feed start -->
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="card-box">
                            <div class="card-head">
                                <header>Activity Feed</header>
                                <button id="feedMenu" class="mdl-button mdl-js-button mdl-button--icon pull-right"
                                        data-upgraded=",MaterialButton">
                                    <i class="material-icons">more_vert</i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
                                    data-mdl-for="feedMenu">
                                    <li class="mdl-menu__item"><i class="material-icons">assistant_photo</i>Action
                                    </li>
                                    <li class="mdl-menu__item"><i class="material-icons">print</i>Another action
                                    </li>
                                    <li class="mdl-menu__item"><i class="material-icons">favorite</i>Something else
                                        here</li>
                                </ul>
                            </div>
                            <div class="card-body ">
                                <ul class="feedBody">
                                    <li class="active-feed">
                                        <div class="feed-user-img">
                                            <img src="../assets/img/std/std1.jpg" class="img-radius "
                                                 alt="User-Profile-Image">
                                        </div>
                                        <h6>
                                            <span class="feedLblStyle lblFileStyle">File</span> Sarah Smith <small
                                                    class="text-muted">6 hours ago</small>
                                        </h6>
                                        <p class="m-b-15 m-t-15">
                                            hii John, I have upload doc related to task.
                                        </p>
                                    </li>
                                    <li class="diactive-feed">
                                        <div class="feed-user-img">
                                            <img src="../assets/img/std/std2.jpg" class="img-radius "
                                                 alt="User-Profile-Image">
                                        </div>
                                        <h6>
                                            <span class="feedLblStyle lblTaskStyle">Task </span> Jalpa Joshi<small
                                                    class="text-muted">5 hours
                                                ago</small>
                                        </h6>
                                        <p class="m-b-15 m-t-15">
                                            Please do as specify. Let me know if you have any query.
                                        </p>
                                    </li>
                                    <li class="diactive-feed">
                                        <div class="feed-user-img">
                                            <img src="../assets/img/std/std3.jpg" class="img-radius "
                                                 alt="User-Profile-Image">
                                        </div>
                                        <h6>
                                            <span class="feedLblStyle lblCommentStyle">comment</span> Lina
                                            Smith<small class="text-muted">6 hours ago</small>
                                        </h6>
                                        <p class="m-b-15 m-t-15">
                                            Hey, How are you??
                                        </p>
                                    </li>
                                    <li class="active-feed">
                                        <div class="feed-user-img">
                                            <img src="../assets/img/std/std4.jpg" class="img-radius "
                                                 alt="User-Profile-Image">
                                        </div>
                                        <h6>
                                            <span class="feedLblStyle lblReplyStyle">Reply</span> Jacob Ryan
                                            <small class="text-muted">7 hours ago</small>
                                        </h6>
                                        <p class="m-b-15 m-t-15">
                                            I am fine. You??
                                        </p>
                                    </li>
                                    <li class="active-feed">
                                        <div class="feed-user-img">
                                            <img src="../assets/img/std/std5.jpg" class="img-radius "
                                                 alt="User-Profile-Image">
                                        </div>
                                        <h6>
                                            <span class="feedLblStyle lblFileStyle">File</span> Sarah Smith <small
                                                    class="text-muted">6 hours ago</small>
                                        </h6>
                                        <p class="m-b-15 m-t-15">
                                            hii John, I have upload doc related to task.
                                        </p>
                                    </li>
                                    <li class="diactive-feed">
                                        <div class="feed-user-img">
                                            <img src="../assets/img/std/std6.jpg" class="img-radius "
                                                 alt="User-Profile-Image">
                                        </div>
                                        <h6>
                                            <span class="feedLblStyle lblTaskStyle">Task </span> Jalpa Joshi<small
                                                    class="text-muted">5 hours
                                                ago</small>
                                        </h6>
                                        <p class="m-b-15 m-t-15">
                                            Please do as specify. Let me know if you have any query.
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Activity feed end -->
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="card-box">
                            <div class="card-head">
                                <header>Exam Toppers</header>
                                <button id="panel-button8"
                                        class="mdl-button mdl-js-button mdl-button--icon pull-right"
                                        data-upgraded=",MaterialButton">
                                    <i class="material-icons">more_vert</i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
                                    data-mdl-for="panel-button8">
                                    <li class="mdl-menu__item"><i class="material-icons">assistant_photo</i>Action
                                    </li>
                                    <li class="mdl-menu__item"><i class="material-icons">print</i>Another action
                                    </li>
                                    <li class="mdl-menu__item"><i class="material-icons">favorite</i>Something else
                                        here</li>
                                </ul>
                            </div>
                            <div class="card-body ">
                                <div class="table-responsive">
                                    <table class="table table-striped custom-table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Roll No</th>
                                            <th>Name</th>
                                            <th>Graph</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>23</td>
                                            <td>John Smith</td>
                                            <td>
                                                <div id="sparkline"></div>
                                            </td>
                                            <td><a href="javascript:void(0)" class="" data-toggle="tooltip"
                                                   title="Edit">
                                                    <i class="fa fa-check"></i></a>
                                                <a href="javascript:void(0)" class="text-inverse" title="Delete"
                                                   data-toggle="tooltip">
                                                    <i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>12</td>
                                            <td>Sneha Pandit</td>
                                            <td>
                                                <div id="sparkline1"></div>
                                            </td>
                                            <td><a href="javascript:void(0)" class="" data-toggle="tooltip"
                                                   title="Edit">
                                                    <i class="fa fa-check"></i></a>
                                                <a href="javascript:void(0)" class="text-inverse" title="Delete"
                                                   data-toggle="tooltip">
                                                    <i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>45</td>
                                            <td>Sarah Smith</td>
                                            <td>
                                                <div id="sparkline2"></div>
                                            </td>
                                            <td><a href="javascript:void(0)" class="" data-toggle="tooltip"
                                                   title="Edit">
                                                    <i class="fa fa-check"></i></a>
                                                <a href="javascript:void(0)" class="text-inverse" title="Delete"
                                                   data-toggle="tooltip">
                                                    <i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>34</td>
                                            <td>John Deo</td>
                                            <td>
                                                <div id="sparkline3"></div>
                                            </td>
                                            <td><a href="javascript:void(0)" class="" data-toggle="tooltip"
                                                   title="Edit">
                                                    <i class="fa fa-check"></i></a>
                                                <a href="javascript:void(0)" class="text-inverse" title="Delete"
                                                   data-toggle="tooltip">
                                                    <i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>15</td>
                                            <td>Jay Soni</td>
                                            <td>
                                                <div id="sparkline4"></div>
                                            </td>
                                            <td><a href="javascript:void(0)" class="" data-toggle="tooltip"
                                                   title="Edit">
                                                    <i class="fa fa-check"></i></a>
                                                <a href="javascript:void(0)" class="text-inverse" title="Delete"
                                                   data-toggle="tooltip">
                                                    <i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="card-box">
                            <div class="card-head">
                                <header>Todo List</header>
                                <button id="panel-button"
                                        class="mdl-button mdl-js-button mdl-button--icon pull-right"
                                        data-upgraded=",MaterialButton">
                                    <i class="material-icons">more_vert</i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
                                    data-mdl-for="panel-button">
                                    <li class="mdl-menu__item"><i class="material-icons">assistant_photo</i>Action
                                    </li>
                                    <li class="mdl-menu__item"><i class="material-icons">print</i>Another action
                                    </li>
                                    <li class="mdl-menu__item"><i class="material-icons">favorite</i>Something else
                                        here</li>
                                </ul>
                            </div>
                            <div class="card-body ">
                                <ul class="to-do-list ui-sortable" id="sortable-todo">
                                    <li class="clearfix">
                                        <div class="todo-check pull-left">
                                            <input type="checkbox" value="None" id="todo-check1">
                                            <label for="todo-check1"></label>
                                        </div>
                                        <p class="todo-title">Add fees details in system
                                        </p>
                                        <div class="todo-actionlist pull-right clearfix">
                                            <a href="#" class="todo-remove"><i class="fa fa-times"></i></a>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="todo-check pull-left">
                                            <input type="checkbox" value="None" id="todo-check2">
                                            <label for="todo-check2"></label>
                                        </div>
                                        <p class="todo-title">Announcement for holiday
                                        </p>
                                        <div class="todo-actionlist pull-right clearfix">
                                            <a href="#" class="todo-remove"><i class="fa fa-times"></i></a>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="todo-check pull-left">
                                            <input type="checkbox" value="None" id="todo-check3">
                                            <label for="todo-check3"></label>
                                        </div>
                                        <p class="todo-title">call bus driver</p>
                                        <div class="todo-actionlist pull-right clearfix">
                                            <a href="#" class="todo-remove"><i class="fa fa-times"></i></a>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="todo-check pull-left">
                                            <input type="checkbox" value="None" id="todo-check4">
                                            <label for="todo-check4"></label>
                                        </div>
                                        <p class="todo-title">School picnic</p>
                                        <div class="todo-actionlist pull-right clearfix">
                                            <a href="#" class="todo-remove"><i class="fa fa-times"></i></a>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="todo-check pull-left">
                                            <input type="checkbox" value="None" id="todo-check5">
                                            <label for="todo-check5"></label>
                                        </div>
                                        <p class="todo-title">Exam time table generate
                                        </p>
                                        <div class="todo-actionlist pull-right clearfix">
                                            <a href="#" class="todo-remove"><i class="fa fa-times"></i></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- start new student list -->
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="card  card-box">
                            <div class="card-head">
                                <header>New Student List</header>
                                <div class="tools">
                                    <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
                                    <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
                                    <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="table-wrap">
                                    <div class="table-responsive">
                                        <table class="table display product-overview mb-30" id="support_table">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Name</th>
                                                <th>Assigned Professor</th>
                                                <th>Date Of Admit</th>
                                                <th>Fees</th>
                                                <th>Branch</th>
                                                <th>Edit</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Jens Brincker</td>
                                                <td>Kenny Josh</td>
                                                <td>27/05/2016</td>
                                                <td>
                                                    <span class="label label-sm label-success">paid</span>
                                                </td>
                                                <td>Mechanical</td>
                                                <td><a href="javascript:void(0)" class="" data-toggle="tooltip"
                                                       title="Edit"><i class="fa fa-check"></i></a>
                                                    <a href="javascript:void(0)" class="text-inverse"
                                                       title="Delete" data-toggle="tooltip"><i
                                                                class="fa fa-trash"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Mark Hay</td>
                                                <td> Mark</td>
                                                <td>26/05/2017</td>
                                                <td>
                                                    <span class="label label-sm label-warning">unpaid </span>
                                                </td>
                                                <td>Science</td>
                                                <td><a href="javascript:void(0)" class="" data-toggle="tooltip"
                                                       title="Edit"><i class="fa fa-check"></i></a>
                                                    <a href="javascript:void(0)" class="text-inverse"
                                                       title="Delete" data-toggle="tooltip"><i
                                                                class="fa fa-trash"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Anthony Davie</td>
                                                <td>Cinnabar</td>
                                                <td>21/05/2016</td>
                                                <td>
                                                    <span class="label label-sm label-success ">paid</span>
                                                </td>
                                                <td>Commerce</td>
                                                <td><a href="javascript:void(0)" class="" data-toggle="tooltip"
                                                       title="Edit"><i class="fa fa-check"></i></a>
                                                    <a href="javascript:void(0)" class="text-inverse"
                                                       title="Delete" data-toggle="tooltip"><i
                                                                class="fa fa-trash"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>David Perry</td>
                                                <td>Felix </td>
                                                <td>20/04/2016</td>
                                                <td>
                                                    <span class="label label-sm label-danger">unpaid</span>
                                                </td>
                                                <td>Mechanical</td>
                                                <td><a href="javascript:void(0)" class="" data-toggle="tooltip"
                                                       title="Edit"><i class="fa fa-check"></i></a>
                                                    <a href="javascript:void(0)" class="text-inverse"
                                                       title="Delete" data-toggle="tooltip"><i
                                                                class="fa fa-trash"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Anthony Davie</td>
                                                <td>Beryl</td>
                                                <td>24/05/2016</td>
                                                <td>
                                                    <span class="label label-sm label-success ">paid</span>
                                                </td>
                                                <td>M.B.A.</td>
                                                <td><a href="javascript:void(0)" class="" data-toggle="tooltip"
                                                       title="Edit"><i class="fa fa-check"></i></a>
                                                    <a href="javascript:void(0)" class="text-inverse"
                                                       title="Delete" data-toggle="tooltip"><i
                                                                class="fa fa-trash"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Alan Gilchrist</td>
                                                <td>Joshep</td>
                                                <td>22/05/2016</td>
                                                <td>
                                                    <span class="label label-sm label-warning ">unpaid</span>
                                                </td>
                                                <td>Science</td>
                                                <td><a href="javascript:void(0)" class="" data-toggle="tooltip"
                                                       title="Edit"><i class="fa fa-check"></i></a>
                                                    <a href="javascript:void(0)" class="text-inverse"
                                                       title="Delete" data-toggle="tooltip"><i
                                                                class="fa fa-trash"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Mark Hay</td>
                                                <td>Jayesh</td>
                                                <td>18/06/2016</td>
                                                <td>
                                                    <span class="label label-sm label-success ">paid</span>
                                                </td>
                                                <td>Commerce</td>
                                                <td><a href="javascript:void(0)" class="" data-toggle="tooltip"
                                                       title="Edit"><i class="fa fa-check"></i></a>
                                                    <a href="javascript:void(0)" class="text-inverse"
                                                       title="Delete" data-toggle="tooltip"><i
                                                                class="fa fa-trash"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>Sue Woodger</td>
                                                <td>Sharma</td>
                                                <td>17/05/2016</td>
                                                <td>
                                                    <span class="label label-sm label-danger">unpaid</span>
                                                </td>
                                                <td>Mechanical</td>
                                                <td><a href="javascript:void(0)" class="" data-toggle="tooltip"
                                                       title="Edit"><i class="fa fa-check"></i></a>
                                                    <a href="javascript:void(0)" class="text-inverse"
                                                       title="Delete" data-toggle="tooltip"><i
                                                                class="fa fa-trash"></i></a></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end new student list -->
            </div>
        </div>
        <!-- end page content -->
        <!-- start chat sidebar -->
        <div class="chat-sidebar-container" data-close-on-body-click="false">
            <div class="chat-sidebar">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="#quick_sidebar_tab_1" class="nav-link active tab-icon" data-toggle="tab"> <i
                                    class="material-icons">chat</i>Chat
                            <span class="badge badge-danger">4</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#quick_sidebar_tab_3" class="nav-link tab-icon" data-toggle="tab"> <i
                                    class="material-icons">settings</i>
                            Settings
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <!-- Start User Chat -->
                    <div class="tab-pane active chat-sidebar-chat in active show" role="tabpanel"
                         id="quick_sidebar_tab_1">
                        <div class="chat-sidebar-list">
                            <div class="chat-sidebar-chat-users slimscroll-style" data-rail-color="#ddd"
                                 data-wrapper-class="chat-sidebar-list">
                                <div class="chat-header">
                                    <h5 class="list-heading">Online</h5>
                                </div>
                                <ul class="media-list list-items">
                                    <li class="media"><img class="media-object" src="../assets/img/prof/prof3.jpg"
                                                           width="35" height="35" alt="...">
                                        <i class="online dot"></i>
                                        <div class="media-body">
                                            <h5 class="media-heading">John Deo</h5>
                                            <div class="media-heading-sub">Spine Surgeon</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-status">
                                            <span class="badge badge-success">5</span>
                                        </div> <img class="media-object" src="../assets/img/prof/prof1.jpg"
                                                    width="35" height="35" alt="...">
                                        <i class="busy dot"></i>
                                        <div class="media-body">
                                            <h5 class="media-heading">Rajesh</h5>
                                            <div class="media-heading-sub">Director</div>
                                        </div>
                                    </li>
                                    <li class="media"><img class="media-object" src="../assets/img/prof/prof5.jpg"
                                                           width="35" height="35" alt="...">
                                        <i class="away dot"></i>
                                        <div class="media-body">
                                            <h5 class="media-heading">Jacob Ryan</h5>
                                            <div class="media-heading-sub">Ortho Surgeon</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-status">
                                            <span class="badge badge-danger">8</span>
                                        </div> <img class="media-object" src="../assets/img/prof/prof4.jpg"
                                                    width="35" height="35" alt="...">
                                        <i class="online dot"></i>
                                        <div class="media-body">
                                            <h5 class="media-heading">Kehn Anderson</h5>
                                            <div class="media-heading-sub">CEO</div>
                                        </div>
                                    </li>
                                    <li class="media"><img class="media-object" src="../assets/img/prof/prof2.jpg"
                                                           width="35" height="35" alt="...">
                                        <i class="busy dot"></i>
                                        <div class="media-body">
                                            <h5 class="media-heading">Sarah Smith</h5>
                                            <div class="media-heading-sub">Anaesthetics</div>
                                        </div>
                                    </li>
                                    <li class="media"><img class="media-object" src="../assets/img/prof/prof7.jpg"
                                                           width="35" height="35" alt="...">
                                        <i class="online dot"></i>
                                        <div class="media-body">
                                            <h5 class="media-heading">Vlad Cardella</h5>
                                            <div class="media-heading-sub">Cardiologist</div>
                                        </div>
                                    </li>
                                </ul>
                                <div class="chat-header">
                                    <h5 class="list-heading">Offline</h5>
                                </div>
                                <ul class="media-list list-items">
                                    <li class="media">
                                        <div class="media-status">
                                            <span class="badge badge-warning">4</span>
                                        </div> <img class="media-object" src="../assets/img/prof/prof6.jpg"
                                                    width="35" height="35" alt="...">
                                        <i class="offline dot"></i>
                                        <div class="media-body">
                                            <h5 class="media-heading">Jennifer Maklen</h5>
                                            <div class="media-heading-sub">Nurse</div>
                                            <div class="media-heading-small">Last seen 01:20 AM</div>
                                        </div>
                                    </li>
                                    <li class="media"><img class="media-object" src="../assets/img/prof/prof8.jpg"
                                                           width="35" height="35" alt="...">
                                        <i class="offline dot"></i>
                                        <div class="media-body">
                                            <h5 class="media-heading">Lina Smith</h5>
                                            <div class="media-heading-sub">Ortho Surgeon</div>
                                            <div class="media-heading-small">Last seen 11:14 PM</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-status">
                                            <span class="badge badge-success">9</span>
                                        </div> <img class="media-object" src="../assets/img/prof/prof9.jpg"
                                                    width="35" height="35" alt="...">
                                        <i class="offline dot"></i>
                                        <div class="media-body">
                                            <h5 class="media-heading">Jeff Adam</h5>
                                            <div class="media-heading-sub">Compounder</div>
                                            <div class="media-heading-small">Last seen 3:31 PM</div>
                                        </div>
                                    </li>
                                    <li class="media"><img class="media-object" src="../assets/img/prof/prof10.jpg"
                                                           width="35" height="35" alt="...">
                                        <i class="offline dot"></i>
                                        <div class="media-body">
                                            <h5 class="media-heading">Anjelina Cardella</h5>
                                            <div class="media-heading-sub">Physiotherapist</div>
                                            <div class="media-heading-small">Last seen 7:45 PM</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End User Chat -->
                    <!-- Start Setting Panel -->
                    <div class="tab-pane chat-sidebar-settings" role="tabpanel" id="quick_sidebar_tab_3">
                        <div class="chat-sidebar-settings-list slimscroll-style">
                            <div class="chat-header">
                                <h5 class="list-heading">Layout Settings</h5>
                            </div>
                            <div class="chatpane inner-content ">
                                <div class="settings-list">
                                    <div class="setting-item">
                                        <div class="setting-text">Sidebar Position</div>
                                        <div class="setting-set">
                                            <select
                                                    class="sidebar-pos-option form-control input-inline input-sm input-small ">
                                                <option value="left" selected="selected">Left</option>
                                                <option value="right">Right</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-text">Header</div>
                                        <div class="setting-set">
                                            <select
                                                    class="page-header-option form-control input-inline input-sm input-small ">
                                                <option value="fixed" selected="selected">Fixed</option>
                                                <option value="default">Default</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-text">Footer</div>
                                        <div class="setting-set">
                                            <select
                                                    class="page-footer-option form-control input-inline input-sm input-small ">
                                                <option value="fixed">Fixed</option>
                                                <option value="default" selected="selected">Default</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat-header">
                                    <h5 class="list-heading">Account Settings</h5>
                                </div>
                                <div class="settings-list">
                                    <div class="setting-item">
                                        <div class="setting-text">Notifications</div>
                                        <div class="setting-set">
                                            <div class="switch">
                                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                                       for="switch-1">
                                                    <input type="checkbox" id="switch-1" class="mdl-switch__input"
                                                           checked>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-text">Show Online</div>
                                        <div class="setting-set">
                                            <div class="switch">
                                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                                       for="switch-7">
                                                    <input type="checkbox" id="switch-7" class="mdl-switch__input"
                                                           checked>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-text">Status</div>
                                        <div class="setting-set">
                                            <div class="switch">
                                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                                       for="switch-2">
                                                    <input type="checkbox" id="switch-2" class="mdl-switch__input"
                                                           checked>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-text">2 Steps Verification</div>
                                        <div class="setting-set">
                                            <div class="switch">
                                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                                       for="switch-3">
                                                    <input type="checkbox" id="switch-3" class="mdl-switch__input"
                                                           checked>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat-header">
                                    <h5 class="list-heading">General Settings</h5>
                                </div>
                                <div class="settings-list">
                                    <div class="setting-item">
                                        <div class="setting-text">Location</div>
                                        <div class="setting-set">
                                            <div class="switch">
                                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                                       for="switch-4">
                                                    <input type="checkbox" id="switch-4" class="mdl-switch__input"
                                                           checked>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-text">Save Histry</div>
                                        <div class="setting-set">
                                            <div class="switch">
                                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                                       for="switch-5">
                                                    <input type="checkbox" id="switch-5" class="mdl-switch__input"
                                                           checked>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-text">Auto Updates</div>
                                        <div class="setting-set">
                                            <div class="switch">
                                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                                       for="switch-6">
                                                    <input type="checkbox" id="switch-6" class="mdl-switch__input"
                                                           checked>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end chat sidebar -->
    </div>
    <!-- end page container -->
    <!-- start footer -->
    <div class="page-footer">
        <div class="page-footer-inner"> 2017 &copy; Smart University Theme By
            <a href="mailto:redstartheme@gmail.com" target="_top" class="makerCss">Redstar Theme</a>
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
    <!-- end footer -->
@endsection
