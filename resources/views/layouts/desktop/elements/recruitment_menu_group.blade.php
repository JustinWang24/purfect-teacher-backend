<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link nav-toggle">
        <i class="material-icons">people</i>
        <span class="title">招生管理</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item">
            <a href="{{ route('school_manager.consult.note') }}" class="nav-link ">
                <span class="title">招生简章/报名须知</span>
                <span class="selected"></span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('teacher.planRecruit.list') }}" class="nav-link ">
                <span class="title">预招管理</span>
                <span class="selected"></span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.consult.list') }}" class="nav-link ">
                <span class="title">咨询管理</span>
                <span class="selected"></span>
            </a>
        </li>
    </ul>
</li>