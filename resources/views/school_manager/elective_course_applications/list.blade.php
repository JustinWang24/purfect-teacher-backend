<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12">
                            <a href="{{ route('school_manager.courses.manager',['uuid'=>session('school.uuid')]) }}" class="btn btn-primary pull-right">
                                添加选修课 <i class="fa fa-plus"></i>
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover valign-middle">
                                <thead>
                                <tr>
                                    <th>提交日期</th>
                                    <th>申请老师</th>
                                    <th>申请抬头</th>
                                    <th>开课条件</th>
                                    <th>目标学生</th>
                                    <th>上课时间</th>
                                    <th>上课地点</th>
                                    <th>当前状态</th>
                                    <th style="width: 100px;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($applications as $index=>$application)
                                    <tr>
                                        <td>{{ _printDate($application->updated_at) }}</td>
                                        <td>{{ $application->teacher_name }}</td>
                                        <td>
                                            <a target="_blank" href="{{ route('teacher.elective-course.edit',['application_id'=>$application->id,'uuid'=>$application->teacher_id]) }}">
                                                {{ $application->name }}
                                            </a>
                                        </td>
                                        <td>最少{{ $application->open_num }}人, 最多{{ $application->max_num }}人</td>
                                        <td>
                                            {{ $application->year }}年级{{$application->term}}学期
                                        </td>
                                        <td>
                                            {{ $application->start_year }}年
                                        </td>
                                        <td></td>
                                        <td>
                                            <span>
                                                {{ $application->getStatusText() }}
                                            </span>
                                        </td>
                                        <td>
                                        <?php
                                            Button::PrintGroup(
                                                [
                                                    'text'=>'可执行操作',
                                                    'subs'=>[
                                                        ['url'=>route('teacher.elective-course.create',['uuid'=>$application->id]),'text'=>'编辑/查看'],
                                                        ['url'=>route('school_manager.elective-course.approve',['uuid'=>$application->id]),'text'=>'同意'],
                                                        ['url'=>route('school_manager.elective-course.refuse',['uuid'=>$application->id]),'text'=>'拒绝'],
                                                    ]
                                                ],
                                                Button::TYPE_PRIMARY
                                            );
                                        ?>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $applications->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection