<div class="row">
    <div class="col-12" id="timetable-mgr-nav-bar">
        <div class="nav-item {{ $app_name === 'timetable_preview_app' ? 'active' : null }}">
            <a href="{{ route('school_manager.timetable.manager.preview',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                <i class="fa fa-plus"></i> 课程表生成工具
            </a>
        </div>
        <div class="nav-item {{ $app_name === 'time_slots_app' ? 'active' : null }}">
            <a href="{{ route('school_manager.timetable.manager',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                <i class="fa fa-clock-o"></i> 上课时间段管理
            </a>
        </div>
        <div class="nav-item {{ $app_name === 'courses_mgr_app' ? 'active' : null }}">
            <a target="_blank" href="{{ route('school_manager.courses.manager',['uuid'=>session('school.uuid')]) }}" class="nav-link">
                <i class="fa fa-list-alt"></i> 课程管理
            </a>
        </div>
    </div>
</div>