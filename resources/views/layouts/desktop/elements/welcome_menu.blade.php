@php
use App\Models\School;
$school = School::find(session('school.id'));
@endphp
<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link nav-toggle">
        <i class="material-icons">local_library</i>
        <span class="title">迎新助手</span>
        <span class="arrow open"></span>
    </a>
    <ul class="sub-menu">
        @foreach($school->campuses as $campus)
        <li class="nav-item">
            <a href="{{ route('school_manager.welcome.manager',['uuid'=>session('school.uuid'),'campus_id'=>$campus->id]) }}" class="nav-link ">
                <span class="title">{{ $campus->name }} - 迎新</span>
            </a>
        </li>
        @endforeach
    </ul>
</li>