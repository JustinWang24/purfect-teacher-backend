<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link nav-toggle">
        <i class="material-icons">dvr</i>
        <span class="title">迎新配置</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item">
		    <ul class="sub-menu">
            <a href="{{ route('welcome_manager.welcomeConfig.index',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">配置</span>
            </a>
        </li>
    </ul>
</li>
