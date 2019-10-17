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

                <!--begin-->
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
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">校园门户管理<span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
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
                    <a href="/recruitStu/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">招生管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">招生管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">招生管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="/welcomeNewStu/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">迎新管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">迎新管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">迎新管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/leaveSchool/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">离校管理1</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">离校管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">离校管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/content/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">内容管理1</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">内容管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">内容管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/teachingTask/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">教务管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">教务管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">教务管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/teaching/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">教学管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">教学管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">教学管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/work/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">办公管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">办公管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">办公管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/equipment/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">设备管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">设备管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">设备管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/repair/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">报修管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">报修管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">报修管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/account/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">账户管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">账户管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">账户管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/getJob/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">就业管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">就业管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">就业管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/train/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">培训管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">培训管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">培训管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/schoolWeb/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">校园网管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">校园网管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">校园网管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/ec/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">电商管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">电商管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">电商管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/community/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">社区管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">社区管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">社区管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/discount/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">优惠管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">优惠管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">优惠管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/vip/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">会员管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">会员管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">会员管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/stuMoney/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">学生币管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">学生币管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">学生币管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/customerService/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">客服管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">客服管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">客服管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/resourcesBit/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">资源位管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">资源位管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">资源位管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/user/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">用户管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">用户管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">用户管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/baseSet/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">基础设置管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">基础设置管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">基础设置管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/data/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">数据管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">数据管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">数据管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/log/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">日志管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">日志管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">日志管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/version/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">版本管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">版本管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">版本管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/role/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">角色管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">角色管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">角色管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/accountNumber/index" class="nav-link nav-toggle">
                        <i class="material-icons">dashboard</i>
                        <span class="title">账号管理</span>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item active">
                            <a href="index.html" class="nav-link ">
                                <span class="title">账号管理 1</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="dashboard2.html" class="nav-link ">
                                <span class="title">账号管理 2</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--新增end-->

            </ul>
        </div>
    </div>
</div>
