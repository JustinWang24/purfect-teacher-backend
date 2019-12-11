<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6 col-xl-6">
            <div class="card-box">
                <div class="card-head">
                    <header>
                        在学校 (<a class="text-primary" href="{{ url('/school_manager/school/view') }}">{{ session('school.name') }}</a>) 的
                        - <a class="text-primary" href="{{ route('school_manager.institute.departments',['uuid'=>$institute->id,'by'=>'institute']) }}">{{ $institute->name }}</a> - 添加新系
                    </header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.department.update') }}" method="post" id="add-department-form">
                        @csrf
                        <input type="hidden" id="institute-id-input" name="department[institute_id]" value="{{ $institute->id }}">
                        <input type="hidden" id="school-id-input" name="department[school_id]" value="{{ session('school.id') }}">

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
                                        @foreach(range(10, \App\Utils\Misc\ConfigurationTool::DEFAULT_STUDY_WEEKS_PER_TERM+2) as $number)
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
                    <header>已创建的系列表</header>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                        <thead>
                        <tr>
                            <th>系名称</th>
                            <th>配置信息</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($institute->departments as $index=>$department)
                            @php /** @var App\Models\Schools\Department $department */ @endphp
                            <tr>
                                <td>
                                    {{ $department->name }}
                                </td>
                                <td>
                                    <p>上自习课是否需要签到: <span class="text-primary">{{ $department->isSelfStudyNeedRegistration() ? '是': '否' }}</span></p>
                                    <p>本系学生每学期可以选择 <span class="text-primary">{{ $department->getOptionalCoursesPerYear() }}</span> 门选修课</p>
                                    <p>本系每学期的教学周数: <span class="text-primary">{{ $department->getStudyWeeksPerTerm() }}</span>周 </p>
                                </td>
                                <td class="text-center">
                                    {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-department','href'=>route('school_manager.department.edit',['uuid'=>$department->id])], Button::TYPE_DEFAULT,'edit') }}
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