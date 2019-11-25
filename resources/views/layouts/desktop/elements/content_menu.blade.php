<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link nav-toggle">
        <i class="material-icons">dvr</i>
        <span class="title">内容管理</span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item">
            <a href="{{ route('school_manager.scenery.list') }}" class="nav-link ">
                <span class="title">基本信息</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.contents.news-manager',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                <span class="title">动态管理</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.contents.regular-manager',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">日常安排</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.contents.questionnaire',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">问卷调查</span>
            </a>
        </li>
    </ul>
</li>
