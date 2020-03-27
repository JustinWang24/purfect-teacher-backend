<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link nav-toggle">
        <i class="material-icons">event_seat</i>
        <span class="title">办公</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item">
            <a href="{{ route('school_manager.notice.list',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">通知公告</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('teacher.list.official.documents',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                <span class="title">公文管理</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('teacher.conference.index',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">会议管理</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.oa.projects-manager',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">项目管理</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.oa.visitors-manager') }}" class="nav-link ">
                <span class="title">来访管理</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.teacher-attendance.manager',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                <span class="title">考勤管理</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.oa.approval-manager',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                <span class="title">选修课程审批</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.students.applications-manager',['uuid'=>session('school.uuid'), 'position' => 1]) }}" class="nav-link">
                <span class="title">申请管理</span>
            </a>
        </li>
    </ul>
</li>
