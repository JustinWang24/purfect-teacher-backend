<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link nav-toggle">
        <i class="material-icons">dvr</i>
        <span class="title">评学管理</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item">
            <a href="{{ route('school_manager.content.list',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                <span class="title">评学管理</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.evaluate-teacher.list',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                <span class="title">评学模板</span>
            </a>
        </li>
    </ul>
</li>
