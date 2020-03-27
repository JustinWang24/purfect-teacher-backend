<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link nav-toggle">
        <i class="material-icons">face</i>
        <span class="title">学生管理</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item">
            <a href="{{ route('school_manager.importer.manager') }}" class="nav-link ">
                <span class="title">新生导入</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.school.students') }}" class="nav-link ">
                <span class="title">学生档案</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.students.applications-manager',['uuid'=>session('school.uuid'), 'position' => 2]) }}" class="nav-link">
                <span class="title">申请管理</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.students.applications-set',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                <span class="title">申请设置</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.students.check-in-manager',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">签到管理</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.students.performances-manager',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">评分管理</span>
            </a>
        </li>
    </ul>
</li>
