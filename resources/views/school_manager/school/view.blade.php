@php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
use App\Utils\Misc\ConfigurationTool;
/**
 * @var \App\Models\Schools\SchoolConfiguration $config
 */
@endphp
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-3 col-lg-3 col-xl-4">
            <div class="card">
                <div class="card-head">
                    <header>{{ session('school.name') }} 基本配置</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('school_manager.school.config.update') }}" method="post"  id="edit-school-config-form">
                                @csrf
                                <input type="hidden" id="school-config-id-input" name="uuid" value="{{ session('school.uuid') }}">
                                <div class="form-group">
                                    <label>默认情况下, 本校学生每学期可以选择</label>
                                    <div class="input-group">
                                        <span class="input-group-addon custom-add-on">本校学生每学期可以选择</span>
                                        <input required type="text" class="form-control"
                                               id="config-1" value="{{ $config ? $config->getOptionalCoursesPerYear() : 1 }}"
                                               placeholder="学生每年可以选择的选修课数量" name="config[{{ \App\Utils\Misc\ConfigurationTool::KEY_OPTIONAL_COURSES_PER_YEAR }}]">
                                        <span class="input-group-addon custom-add-on">门选修课</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>学生每学期的教学周数</label>
                                    <select class="form-control" id="room-type-input" name="config[{{ \App\Utils\Misc\ConfigurationTool::KEY_STUDY_WEEKS_PER_TERM }}]">
                                        @php
                                            $weeksPerTerm = $config ? $config->getStudyWeeksPerTerm() : \App\Utils\Misc\ConfigurationTool::DEFAULT_STUDY_WEEKS_PER_TERM;
                                        @endphp
                                        @foreach(range(10, \App\Utils\Misc\ConfigurationTool::DEFAULT_STUDY_WEEKS_PER_TERM + 2) as $number)
                                            <option value="{{ $number }}" {{ $number===$weeksPerTerm ? 'selected':null }}>{{ $number }}周</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>学生上自习课, 是否需要签到</label>
                                    @php
                                        $needRegistration = $config ? $config->isSelfStudyNeedRegistration() : false;
                                    @endphp
                                    <select class="form-control" id="room-type-input" name="config[{{ \App\Utils\Misc\ConfigurationTool::KEY_SELF_STUDY_NEED_REGISTRATION }}]">
                                        <option value="1" {{ $needRegistration ? 'selected':null }}>自习课需要签到</option>
                                        <option value="0" {{ !$needRegistration ? 'selected':null }}>自习课不需要签到</option>
                                    </select>
                                </div>

                                <?php
                                Button::Print(['id'=>'btn-save-school-config','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                                ?>&nbsp;
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-9 col-lg-9 col-xl-8">
            <div class="card">
                <div class="card-head">
                    <header>
                        {{ session('school.name') }}
                        <a href="{{ route('school_manager.campus.add') }}" class="btn btn-primary" id="btn-create-campus-from-school">
                            创建新校区 <i class="fa fa-plus"></i>
                        </a>
                    </header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12">
                            @include('school_manager.school.reusable.nav',['highlight'=>'campus'])
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>校区名称</th>
                                    <th style="width: 300px;">校区设施</th>
                                    <th style="width: 100px;text-align: center;">学院数</th>
                                    <th style="width: 100px;">教职工总数</th>
                                    <th style="width: 100px;">学生总数</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                               
                                @foreach($school->campuses as $index=>$campus)
                                    @php /** @var App\Models\Schools\Campus $campus */ @endphp
                                    <tr>
                                        <td>
                                            {{ $campus->name }}
                                        </td>
                                        <td>
                                            <a class="anchor-building-counter" href="{{ route('school_manager.campus.buildings',['uuid'=>$campus->id,'by'=>'campus', 'type'=>\App\Models\Schools\Building::TYPE_CLASSROOM_BUILDING]) }}">
                                                <span class="badge badge-light">教学楼: {{ count($campus->classroomBuildings) }}</span>
                                            </a>
                                            <a class="anchor-building-counter" href="{{ route('school_manager.campus.buildings',['uuid'=>$campus->id,'by'=>'campus', 'type'=>\App\Models\Schools\Building::TYPE_STUDENT_HOSTEL_BUILDING]) }}">
                                                <span class="badge badge-light">学生宿舍楼: {{ count($campus->hostels) }}</span>
                                            </a>
                                            <a class="anchor-building-counter" href="{{ route('school_manager.campus.buildings',['uuid'=>$campus->id,'by'=>'campus', 'type'=>\App\Models\Schools\Building::TYPE_HALL]) }}">
                                                <span class="badge badge-light">会堂/食堂/会议室: {{ count($campus->halls) }}</span>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a class="anchor-institute-counter" href="{{ route('school_manager.campus.institutes',['uuid'=>$campus->id,'by'=>'campus']) }}">
                                                {{ count($campus->institutes) }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a class="employees-counter" href="{{ route('school_manager.campus.users',['type'=>User::TYPE_EMPLOYEE,'uuid'=>$campus->id,'by'=>'campus']) }}">{{ $campus->employeesCount() }}</a>
                                        </td>
                                        <td class="text-center">
                                            <a class="students-counter" href="{{ route('school_manager.campus.users',['type'=>User::TYPE_STUDENT,'uuid'=>$campus->id,'by'=>'campus']) }}">{{ $campus->studentsCount() }}</a>
                                        </td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-campus','href'=>route('school_manager.campus.edit',['uuid'=>$campus->id])], Button::TYPE_DEFAULT,'edit') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
