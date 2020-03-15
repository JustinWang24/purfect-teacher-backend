@php
use App\Models\School;
$school = School::find(session('school.id'));
@endphp
<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link nav-toggle">
        <i class="material-icons">local_library</i>
        <span class="title">迎新管理</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item">
            <a href="{{ route('welcome_manager.welcomeConfig.index',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">配置</span>
            </a>
        </li>
		<li class="nav-item">
            <a href="{{ route('welcome_manager.welcomeReport.wait_list',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">迎新-待报到</span>
            </a>
        </li>
		<li class="nav-item">
            <a href="{{ route('welcome_manager.welcomeReport.processing_list',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">迎新-报到中</span>
            </a>
        </li>
		<li class="nav-item">
            <a href="{{ route('welcome_manager.welcomeReport.completed_list',['uuid'=>session('school.uuid')]) }}" class="nav-link ">
                <span class="title">迎新-已完成</span>
            </a>
        </li>
		<li class="nav-item">
            <a href="{{ route('welcome_manager.welcomeReport.tuitionfee_list',['uuid'=>session('school.uuid'),'typeid'=>1,'index'=>1]) }}" class="nav-link ">
                <span class="title">学费-未交费</span>
            </a>
        </li>
		<li class="nav-item">
            <a href="{{ route('welcome_manager.welcomeReport.tuitionfee_list',['uuid'=>session('school.uuid'),'typeid'=>1,'index'=>2]) }}" class="nav-link ">
                <span class="title">学费-已交费</span>
            </a>
        </li>
		<li class="nav-item">
            <a href="{{ route('welcome_manager.welcomeReport.bookfee_list',['uuid'=>session('school.uuid'),'typeid'=>2,'index'=>1]) }}" class="nav-link ">
                <span class="title">书费-未交费</span>
            </a>
        </li>
		<li class="nav-item">
            <a href="{{ route('welcome_manager.welcomeReport.bookfee_list',['uuid'=>session('school.uuid'),'typeid'=>2,'index'=>2]) }}" class="nav-link ">
                <span class="title">书费-已交费</span>
            </a>
        </li>
		<li class="nav-item">
            <a href="{{ route('welcome_manager.welcomeReport.roomfee_list',['uuid'=>session('school.uuid'),'typeid'=>3,'index'=>1]) }}" class="nav-link ">
                <span class="title">住宿费-未交费</span>
            </a>
        </li>
		<li class="nav-item">
            <a href="{{ route('welcome_manager.welcomeReport.roomfee_list',['uuid'=>session('school.uuid'),'typeid'=>3,'index'=>2]) }}" class="nav-link ">
                <span class="title">住宿费-已交费</span>
            </a>
        </li>
    </ul>
</li>