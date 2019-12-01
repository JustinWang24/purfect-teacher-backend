<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link nav-toggle">
        <i class="material-icons">event_seat</i>
        <span class="title">WIFI报修</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item">
            <a href="{{ route('manager_wifi.wifiIssue.list',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">报修列表</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('manager_wifi.wifiIssueType.list',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                <span class="title">报修分类</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('manager_wifi.wifiIssueComment.list',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">报修评论列表</span>
            </a>
        </li>
    </ul>
</li>
