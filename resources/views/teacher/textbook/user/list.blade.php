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
                    <header>{{ $parent->name??session('school.name') }}(学生总数: {{ $students->total() }})</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12">

                            @include('school_manager.school.reusable.search',['highlight'=>'student'])
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover
                             table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>姓名</th>
                                    <th>学号</th>
                                    <th>所在班级</th>
                                    <th>缴费状态</th>
                                    <th>状态</th>
                                    <th>领取时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($students as $index=>$gradeUser)
                                    @php
                                        /** @var \App\Models\Users\GradeUser $gradeUser */
                                        /**计算当前学生领书情况*/
                                    $studentTextbook = $gradeUser->user->getStudentLastTextbookByYear($gradeYear)
                                    @endphp

                                    <tr>
                                        <td>{{ $gradeUser->user->id }}</td>
                                        <td>
                                            {{ $gradeUser->user->name ?? 'n.a' }}
                                        </td>
                                        <td>{{ $gradeUser->user->profile->student_number?? '' }}</td>
                                        <td>{{ $gradeUser->studyAt() }}</td>
                                        <td></td>
                                        <td>{{ $studentTextbook?'已领取':'未领取' }}</td>
                                        <td>{{ $studentTextbook?$studentTextbook->created_at:'' }}</td>
                                        <td class="text-center">
                                            {{ \App\Utils\UI\Anchor::Print(['text'=>'查看','class'=>'btn-edit-facility',
                                            'href'=>route('school_manager.textbook.users',
                                            ['user_id'=>$gradeUser->user_id,'year'=>$gradeYear])],
                                             \App\Utils\UI\Button::TYPE_INFO,'info') }}

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
