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
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header class="full-width">
                        {{ session('school.name') }} {{ $parent->name??'' }}
                        @if(isset($parent))
                            <a href="{{ route('school_manager.campus.institutes',['uuid'=>$parent->campus_id, 'by'=>'campus']) }}" class="btn btn-default pull-right">
                                返回 <i class="fa fa-arrow-circle-left"></i>
                            </a>&nbsp;
                            <a href="{{ route('school_manager.department.add',['uuid'=>$parent->id]) }}" class="btn btn-primary pull-right" id="btn-create-department-from-institute">
                                创建新系 <i class="fa fa-plus"></i>
                            </a>
                        @else
                            <a href="{{ route('school_manager.school.view') }}" class="btn btn-default pull-right">
                                返回 <i class="fa fa-arrow-circle-left"></i>
                            </a>&nbsp;
                        @endif
                    </header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12 pt-0">
                            @include('school_manager.school.reusable.nav',['highlight'=>'department'])
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>系名称</th>
                                    <th>系主任</th>
                                    <th style="width: 300px;">教学相关配置</th>
                                    <th>专业数</th>
                                    <th>教职工数</th>
                                    <th>学生数</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($departments as $index=>$department)
                                    <tr>
                                        <td>
                                            {{ $department->name }}
                                        </td>
                                        <td>
                                            @if($department->adviser)
                                                {{ $department->adviser->user_name }} &nbsp;
                                                @if(\Illuminate\Support\Facades\Auth::user()->isSchoolAdminOrAbove())
                                                    <a href="{{ route('school_manager.department.set.adviser',['department'=>$department->id]) }}">(编辑)</a>
                                                @endif
                                            @else
                                                @if(\Illuminate\Support\Facades\Auth::user()->isSchoolAdminOrAbove())
                                                <a href="{{ route('school_manager.department.set.adviser',['department'=>$department->id]) }}">设置系主任</a>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <p>上自习课是否需要签到: <span class="text-primary">{{ $department->isSelfStudyNeedRegistration() ? '是': '否' }}</span></p>
                                            <p>本系学生每学期可以选择 <span class="text-primary">{{ $department->getOptionalCoursesPerYear() }}</span> 门选修课</p>
                                            <p>本系每学期的教学周数: <span class="text-primary">{{ $department->getStudyWeeksPerTerm() }}</span>周 </p>
                                        </td>
                                        <td class="text-center">
                                            <a class="anchor-majors-counter" href="{{ route('school_manager.department.majors',['uuid'=>$department->id,'by'=>'department']) }}">{{ count($department->majors) }}</a>
                                        </td>
                                        <td class="text-center">
                                            <a class="employees-counter" href="{{ route('school_manager.institute.users',['type'=>User::TYPE_EMPLOYEE,'by'=>'department','uuid'=>$department->id]) }}">{{ $department->employeesCount() }}</a>
                                        </td>
                                        <td class="text-center">
                                            <a class="students-counter" href="{{ route('school_manager.institute.users',['type'=>User::TYPE_STUDENT,'by'=>'department','uuid'=>$department->id]) }}">{{ $department->studentsCount() }}</a>
                                        </td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-major','href'=>route('school_manager.department.edit',['uuid'=>$department->id])], Button::TYPE_DEFAULT,'edit') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            @if(!isset($parent))
{{ $departments->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
