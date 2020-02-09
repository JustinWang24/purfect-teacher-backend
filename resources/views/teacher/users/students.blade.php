<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        {{ $parent->name??session('school.name') }}(学生总数: {{ $students->total() }})
                        @if(isset($parent) && get_class($parent) === 'App\Models\Schools\Grade')
                            @if($parent->gradeManager)
                                <a href="{{ route('school_manager.grade.set-adviser',['grade'=>$parent->id]) }}">
                                @if($parent->gradeManager->adviser_id)
                                    班主任: {{ $parent->gradeManager->adviser_name }}
                                @else
                                    设置班主任
                                @endif
                                </a>

                                <a href="{{ route('teacher.grade.set-monitor',['grade'=>$parent->id]) }}">
                                    @if($parent->gradeManager->monitor_id)
                                        班长: {{ $parent->gradeManager->monitor_name }}
                                    @else
                                        设置班长
                                    @endif
                                </a>
                            @else

                            @endif
                        @endif

                        <a href="{{ route('school_manager.student.add') }}" class="btn btn-primary pull-right">
                            添加新学生 <i class="fa fa-plus"></i>
                        </a>
                    </header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12 pt-0">
                            @include('school_manager.school.reusable.nav',['highlight'=>'student'])
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>学号</th>
                                    <th>头像</th>
                                    <th>姓名</th>
                                    <th>联系电话</th>
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
                                        <td>{{ $gradeUser->studentProfile->serial_number??$gradeUser->id }}</td>
                                        <td>
                                            <img src="{{ $gradeUser->studentProfile->avatar??null }}" style="width: 60px;border-radius: 50%;">
                                        </td>
                                        <td>
                                            {{ $gradeUser->user->name ?? 'n.a' }}
                                            @if(isset($parent) && get_class($parent) === 'App\Models\Schools\Grade')
                                                @if(isset($parent->gradeManager->monitor_id))
                                                    @if($parent->gradeManager->monitor_id === $gradeUser->user_id)
                                                        <span class="text-primary">(班长)</span>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            {{ $gradeUser->user->mobile }}
                                            {{ $gradeUser->user->getStatusText() }}
                                        </td>
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
                                                        ['url'=>route('verified_student.profile.edit',['uuid'=>$gradeUser->user->uuid]),'text'=>'编辑'],
                                                        ['url'=>route('teacher.student.edit-avatar',['uuid'=>$gradeUser->user->uuid]),'text'=>'照片'],
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
                                {{ isset($appendedParams) ? $students->appends($appendedParams)->links() : $students->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
