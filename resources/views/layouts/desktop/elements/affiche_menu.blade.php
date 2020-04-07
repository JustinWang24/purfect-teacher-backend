<li class="nav-item">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="material-icons">camera</i>
        <span class="title">社区管理</span>
        <span class="arrow "></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-university"></i> 社区列表
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item">
                    <a href="{{ route('teacher.community.dynamic',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                        <span class="title">动态列表</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('teacher.community.dynamic.type',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                        <span class="title">分类列表</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('teacher.community.communities',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                        <span class="title">社团列表</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-gavel"></i> 动态管理
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item">
                    <a href="{{ route('manager_affiche.affiche.top_affiche_list',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                        <span class="title">推荐</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('manager_affiche.affiche.affiche_pending_list',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                        <span class="title">待审核</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('manager_affiche.affiche.affiche_adopt_list',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                        <span class="title">已通过</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-group"></i>社群管理
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item">
                    <a href="{{ route('manager_affiche.group.group_pending_list',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                        <span class="title">待审核</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('manager_affiche.group.group_adopt_list',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                        <span class="title">已通过</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</li>

