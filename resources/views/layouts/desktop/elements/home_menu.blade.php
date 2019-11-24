<li class="nav-item {{ \App\Utils\Misc\Nav::IsBasicNav() ? 'active' : null }}">
    <a href="{{ route('school_manager.school.view') }}" class="nav-link">
        <i class="material-icons">business</i>
        <span class="title">{{ session('school.name') }}</span>
    </a>
</li>