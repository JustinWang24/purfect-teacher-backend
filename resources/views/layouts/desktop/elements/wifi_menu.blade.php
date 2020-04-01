<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link nav-toggle">
        <i class="material-icons">wifi</i>
        <span class="title">WIFI</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item">
            <a href="{{ route('manager_wifi.wifi.list',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">充值配置</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('manager_wifi.wifiOrder.list',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                <span class="title">充值列表</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('manager_wifi.wifiContent.list',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">公告列表</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('manager_wifi.wifiNotice.list',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">文档列表</span>
            </a>
        </li>

    </ul>
</li>
