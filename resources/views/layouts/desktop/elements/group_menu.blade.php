<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link nav-toggle">
        <i class="material-icons">dvr</i>
        <span class="title">社群管理</span>
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
