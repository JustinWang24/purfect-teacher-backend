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

                    </header>
                </div>
                <div class="card-body">
                    <div class="row">


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
                                            <a  href="{{ route('school_manager.evaluate-teacher.student',['grade_id'=>$grade->id]) }}" class="btn btn-round btn-primary btn-view-timetable">
                                                <i class="fa ">查看学生</i></a>
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
