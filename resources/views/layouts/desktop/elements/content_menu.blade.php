<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link nav-toggle">
        <i class="material-icons">dvr</i>
        <span class="title">内容管理</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item">
            <a href="{{ route('school_manager.contents.news-manager',['uuid'=>session('school.uuid'),'type'=>\App\Models\Schools\News::TYPE_SCIENCE]) }}" class="nav-link">
                <span class="title">科技成果</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.contents.news-manager',['uuid'=>session('school.uuid'),'type'=>\App\Models\Schools\News::TYPE_CAMPUS]) }}" class="nav-link">
                <span class="title">校园风采</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.contents.photo-album',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                <span class="title">校园相册</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('school_manager.contents.news-manager',['uuid'=>session('school.uuid'),'type'=>\App\Models\Schools\News::TYPE_NEWS]) }}" class="nav-link">
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
        <li class="nav-item">
            <a href="{{ route('school_manager.banner.list',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">资源位管理</span>
            </a>
        </li>
    </ul>
</li>
