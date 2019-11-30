<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
/**
 * @var App\Models\Schools\Department $department
 */
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6 col-xl-6">
            <div class="card-box">
                <div class="card-head">
                    <header>
                        修改学校 (<a class="text-primary" href="{{ url('/school_manager/school/view') }}">{{ session('school.name') }}</a>) 的
                        - <a class="text-primary" href="{{ route('school_manager.institute.departments',['uuid'=>$institute->id,'by'=>'institute']) }}">{{ $institute->name }}</a> - 的 {{ $department->name }}
                    </header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.department.update') }}" method="post" id="edit-department-form">
                        @csrf
                        <input type="hidden" id="department-id-input" name="department[id]" value="{{ $department->id }}">
                        <input type="hidden" id="department-institute-id-input" name="department[institute_id]" value="{{ $department->institute_id }}">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="department-name-input">系名称</label>
                                    <input required type="text" class="form-control" id="department-name-input" value="{{ $department->name }}" placeholder="系名称" name="department[name]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>本系学生每学期的教学周数</label>
                                    <select class="form-control" id="config2-input" name="department[{{ \App\Utils\Misc\ConfigurationTool::KEY_STUDY_WEEKS_PER_TERM }}]">
                                        @foreach(range(10, \App\Utils\Misc\ConfigurationTool::DEFAULT_STUDY_WEEKS_PER_TERM + 2) as $number)
                                            <option value="{{ $number }}" {{ $number===$department->getStudyWeeksPerTerm() ? 'selected':null }}>{{ $number }}周</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>本系学生上自习课, 是否需要签到</label>
                                    <select class="form-control" id="room-type-input" name="department[{{ \App\Utils\Misc\ConfigurationTool::KEY_SELF_STUDY_NEED_REGISTRATION }}]">
                                        <option value="1" {{ $department->isSelfStudyNeedRegistration() ? 'selected':null }}>自习课需要签到</option>
                                        <option value="0" {{ !$department->isSelfStudyNeedRegistration() ? 'selected':null }}>自习课不需要签到</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon custom-add-on">本系学生每学期可以选择</span>
                                <input required type="text" class="form-control"
                                       id="config-1" value="{{ $department->getOptionalCoursesPerYear() }}"
                                       placeholder="学生每年可以选择的选修课数量" name="department[{{ \App\Utils\Misc\ConfigurationTool::KEY_OPTIONAL_COURSES_PER_YEAR }}]">
                                <span class="input-group-addon custom-add-on">门选修课</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="department-desc-input">简介</label>
                            <textarea class="form-control" name="department[description]" id="department-desc-input" cols="30" rows="10" placeholder="系简介">{{ $department->description }}</textarea>
                        </div>
                        <?php
                        Button::Print(['id'=>'btn-save-department','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-6">
            <div class="card-box">
                <div class="card-head">
                    <header>班级列表</header>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                        <thead>
                        <tr>
                            <th>班级名称</th>
                            <th style="width: 100px;">班级数</th>
                            <th style="width: 100px;">教职工总数</th>
                            <th style="width: 100px;">学生总数</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($department->majors as $index=>$major)
                            @php /** @var App\Models\Schools\Major $major */ @endphp
                            <tr>
                                <td>
                                    {{ $major->name }}
                                </td>
                                <td class="text-center">
                                    <a class="anchor-grades-counter" href="{{ route('school_manager.major.grades',['uuid'=>$major->id,'by'=>'major']) }}">{{ count($major->grades) }}</a>
                                </td>
                                <td class="text-center">
                                    <a class="students-counter" href="{{ route('school_manager.major.users',['type'=>User::TYPE_EMPLOYEE,'uuid'=>$major->id,'by'=>'major']) }}">
                                        {{ $major->employeesCount() }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a class="students-counter" href="{{ route('school_manager.major.users',['type'=>User::TYPE_STUDENT,'uuid'=>$major->id,'by'=>'major']) }}">
                                        {{ $major->studentsCount() }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-major','href'=>route('school_manager.major.edit',['uuid'=>$major->id])], Button::TYPE_DEFAULT,'edit') }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection