<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
$years = \App\Utils\Time\GradeAndYearUtil::GetAllYears();
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        {{ session('school.name') }} - {{ $parent->name??'' }} {{ isset($year) ? $year.'级' : null }}
                        @if(isset($yearManager))
                            <a href="{{ route('school_manager.school.set-year-manager',['year'=>$year]) }}">{{ $yearManager->user->name ?? '设置年级组长' }}</a>
                        @else
                            @if(isset($year))
                            <a href="{{ route('school_manager.school.set-year-manager',['year'=>$year]) }}">设置年级组长</a>
                            @endif
                        @endif

                        <p class="pull-right">
                            @foreach($years as $y)
                                <a class="btn btn-{{ $year == $y ? 'primary' : 'default' }} btn-sm" href="{{ route('school_manager.school.years',['year'=>$y]) }}">{{ $y }}级</a>
                            @endforeach
                        </p>
                    </header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12">
                            @if(isset($parent))
                                <a href="{{ route('school_manager.department.majors',['uuid'=>$parent->department->id,'by'=>'department']) }}" class="btn btn-default">
                                    <i class="fa fa-arrow-circle-left"></i> 返回
                                </a>&nbsp;
                                <a href="{{ route('school_manager.grade.add',['uuid'=>$parent->id]) }}" class="btn btn-primary" id="btn-create-brade-from-major">
                                    创建班级 <i class="fa fa-plus"></i>
                                </a>
                            @endif
                            @include('school_manager.school.reusable.nav',['highlight'=>isset($year)? 'years':'grade'])
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>入学年份</th>
                                    <th>班级名称</th>
                                    <th>班主任</th>
                                    <th>班长</th>
                                    <th class="text-center">学生数</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($grades as $index=>$grade)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $grade->year }} 年</td>
                                        <td>
                                            {{ $grade->name }}
                                        </td>
                                        <td>
                                            @if($grade->gradeManager)
                                                {{ $grade->gradeManager->adviser_name }}
                                                @if(\Illuminate\Support\Facades\Auth::user()->isSchoolAdminOrAbove())
                                                    <a href="{{ route('school_manager.grade.set-adviser',['grade'=>$grade->id]) }}">(编辑)</a>
                                                @endif
                                            @else
                                                @if(\Illuminate\Support\Facades\Auth::user()->isSchoolAdminOrAbove())
                                                    <a href="{{ route('school_manager.grade.set-adviser',['grade'=>$grade->id]) }}">设置班主任</a>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($grade->gradeManager)
                                                {{ $grade->gradeManager->monitor_name }}
                                                @if(\Illuminate\Support\Facades\Auth::user()->isSchoolAdminOrAbove() || \Illuminate\Support\Facades\Auth::user()->isTeacher())
                                                    <a href="{{ route('teacher.grade.set-monitor',['grade'=>$grade->id]) }}">(编辑)</a>
                                                @endif
                                            @else
                                                @if(\Illuminate\Support\Facades\Auth::user()->isSchoolAdminOrAbove() || \Illuminate\Support\Facades\Auth::user()->isTeacher())
                                                    <a href="{{ route('teacher.grade.set-monitor',['grade'=>$grade->id]) }}">设置班长</a>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a class="students-counter" href="{{ route('teacher.grade.users',['type'=>User::TYPE_STUDENT,'by'=>'grade','uuid'=>$grade->id]) }}">{{ $grade->studentsCount() }}</a>
                                        </td>
                                        <td class="text-center">
                                            <a target="_blank" href="{{ route('school_manager.textbook.grade',['type'=>User::TYPE_STUDENT,'by'=>'grade','uuid'=>$grade->id]) }}" class="btn btn-round btn-primary btn-view-timetable">
                                                <i class="fa ">教材</i></a>
                                            <a target="_blank" href="{{ route('school_manager.grade.view.timetable',['uuid'=>$grade->id]) }}" class="btn btn-round btn-primary btn-view-timetable">
                                                <i class="fa fa-calendar"></i>查看课表
                                            </a>
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-grade','href'=>route('school_manager.grade.edit',['uuid'=>$grade->id])], Button::TYPE_DEFAULT,'edit') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @if(!isset($parent))
                                {{ $grades->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
