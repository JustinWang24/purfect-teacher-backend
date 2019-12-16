<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link nav-toggle">
        <i class="material-icons">dvr</i>
        <span class="title">社区管理</span>
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
    </ul>
</li>
