<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>{{ $parent->name }}(学生总数: {{ $students->total() }})</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">
                            <div class="col-12">
                                <a href="{{ url()->previous() }}" class="btn btn-default">
                                    返回 <i class="fa fa-arrow-circle-left"></i>
                                </a>&nbsp;
                                <a href="{{ route('school_manager.student.add') }}" class="btn btn-primary pull-right">
                                    添加新学生 <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>姓名</th>
                                    <th>状态</th>
                                    <th>所在班级</th>
                                    <th>待办的申请</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($students as $index=>$gradeUser)
                                    @php
                                        /** @var \App\Models\Users\GradeUser $gradeUser */
                                    @endphp
                                    <tr>
                                        <td>{{ $gradeUser->user->id }}</td>
                                        <td>
                                            {{ $gradeUser->user->name ?? 'n.a' }}
                                        </td>
                                        <td>{{ $gradeUser->user->getStatusText() }}</td>
                                        <td>{{ $gradeUser->studyAt() }}</td>
                                        <td>{{ count($gradeUser->enquiries) }}</td>
                                        <td class="text-center">
                                            <a target="_blank" href="{{ route('school_manager.grade.view.timetable',['uuid'=>$gradeUser->grade_id]) }}" class="btn btn-round btn-primary btn-view-timetable">
                                                <i class="fa fa-calendar"></i>查看课表
                                            </a>
                                            @php
                                            Button::PrintGroup(
                                                [
                                                    'text'=>'可执行操作',
                                                    'subs'=>[
                                                        ['url'=>route('school_manager.student.edit',['uuid'=>$gradeUser->user->uuid]),'text'=>'编辑'],
                                                        ['url'=>route('school_manager.student.suspend',['uuid'=>$gradeUser->user->uuid]),'text'=>'休学'],
                                                        ['url'=>route('school_manager.student.stop',['uuid'=>$gradeUser->user->uuid]),'text'=>'停课'],
                                                        ['url'=>route('school_manager.student.reject',['uuid'=>$gradeUser->user->uuid]),'text'=>'退学'],
                                                    ]
                                                ],
                                                Button::TYPE_PRIMARY
                                            )
                                            @endphp
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                {{ $students->appends($appendedParams)->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
