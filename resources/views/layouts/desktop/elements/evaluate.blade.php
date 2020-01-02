<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link nav-toggle">
        <i class="material-icons">dvr</i>
        <span class="title">评教模块</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item">
            <a href="{{ route('school_manager.evaluate.content-list',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                <span class="title">评教内容</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.evaluate.teacher-list',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                <span class="title">评教老师</span>
            </a>
        </li>
    </ul>
</li>
