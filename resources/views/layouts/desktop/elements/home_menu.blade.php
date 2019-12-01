<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link nav-toggle">
        <i class="material-icons">business</i>
        <span class="title">基础信息</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item">
            <a href="{{ route('school_manager.school.view') }}" class="nav-link ">
                <span class="title">学校管理</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.school.organization-manager',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                <span class="title">组织架构</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('verified_student.contacts.list') }}" class="nav-link">
                <span class="title">通讯录</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.facility.list') }}" class="nav-link">
                <span class="title">设备管理</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manger.school.calendar.index',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">校历</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manger.configs.performance-teacher') }}" class="nav-link ">
                <span class="title">教职工工作考评</span>
            </a>
        </li>
    </ul>
</li>
