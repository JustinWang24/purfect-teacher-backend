<div>
    <div id="quick-search-current-school-id" data-school="{{ session('school.id') }}"></div>
    <div id="quick-search-current-scope" data-scope="{{ $highlight }}"></div>
    @include('school_manager.school.reusable.search')
    <div class="btn-group pull-right">
        @if($highlight !== 'campus')
            <a href="{{ route('school_manager.school.view') }}" class="btn btn-{{ $highlight==='campus' ? 'primary' : 'default' }}">
                <span class="fa {{ $highlight==='campus' ? 'fa-check-square' : null }}"></span> 校区管理
            </a>
        @endif
        <a href="{{ route('school_manager.school.rooms') }}" class="btn btn-{{ $highlight==='room' ? 'primary' : 'default' }}">
            <span class="fa {{ $highlight==='room' ? 'fa-check-square' : null }}"></span> 物业管理
        </a>
        <a href="{{ route('school_manager.school.institutes',['for'=>'institutes']) }}" class="btn btn-{{ $highlight==='institute' ? 'primary' : 'default' }}">
            <span class="fa {{ $highlight==='institute' ? 'fa-check-square' : null }}"></span> 学院管理
        </a>
        <a href="{{ route('school_manager.school.departments') }}" class="btn btn-{{ $highlight==='department' ? 'primary' : 'default' }}">
            <span class="fa {{ $highlight==='department' ? 'fa-check-square' : null }}"></span> 系管理
        </a>
        <a href="{{ route('school_manager.school.majors') }}" class="btn btn-{{ $highlight==='major' ? 'primary' : 'default' }}">
            <span class="fa {{ $highlight==='major' ? 'fa-check-square' : null }}"></span> 专业管理
        </a>
        <a href="{{ route('school_manager.school.years') }}" class="btn btn-{{ $highlight==='years' ? 'primary' : 'default' }}">
            <span class="fa {{ $highlight==='years' ? 'fa-check-square' : null }}"></span> 年级管理
        </a>
        <a href="{{ route('school_manager.school.grades') }}" class="btn btn-{{ $highlight==='grade' ? 'primary' : 'default' }}">
            <span class="fa {{ $highlight==='grade' ? 'fa-check-square' : null }}"></span> 班级管理
        </a>
        <a href="{{ route('school_manager.school.teachers') }}" class="btn btn-{{ $highlight==='teacher' ? 'primary' : 'default' }}">
            <span class="fa {{ $highlight==='teacher' ? 'fa-check-square' : null }}"></span> 教师管理
        </a>
        <a href="{{ route('school_manager.school.students') }}" class="btn btn-{{ $highlight==='student' ? 'primary' : 'default' }}">
            <span class="fa {{ $highlight==='student' ? 'fa-check-square' : null }}"></span> 学生管理
        </a>
    </div>
</div>
