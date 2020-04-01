<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link nav-toggle">
        <i class="material-icons">settings</i>
        <span class="title">系统设置</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item">
            <a href="{{ route('admin.versions.list') }}" class="nav-link ">
                <span class="title">版本号管理</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.proposal.list') }}" class="nav-link ">
                <span class="title">意见反馈</span>
            </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.notifications.list',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
            <span class="title">消息中心</span>
          </a>
        </li>
    </ul>
</li>


