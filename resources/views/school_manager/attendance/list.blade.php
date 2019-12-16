<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header>{{ session('school.name') }} 值周任务</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12">

                                <a href="{{ route('school_manager.attendance.add') }}" class="btn btn-primary " id="btn-create-attendance-from">
                                    创建新值周任务 <i class="fa fa-plus"></i>
                                </a>


                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>值周任务名称</th>
                                    <th>具体内容</th>
                                    <th>类型</th>
                                    <th>开始和截止时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($tasks) == 0)
                                    <tr>
                                        <td colspan="6">还没有内容 </td>

                                    </tr>
                                @endif
                                @foreach($tasks as $index=>$task)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td><a href="/school_manager/attendance/schedule/display/{{$task->id}}">{{ $task->title }}</a></td>
                                        <td>{{ $task->content }}</td>
                                        <td>
                                                @if($task->type==1) 教师任务
                                                @elseif($task->type==2) 学生任务
                                                @elseif($task->type==3) 混合任务
                                                @endif

                                        </td>
                                        <td class="text-center">
                                            从{{$task->start_time}} 开始到 {{$task->end_time}}结束
                                        </td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-building','href'=>route('school_manager.attendance.edit',['id'=>$task->id])], Button::TYPE_DEFAULT,'edit') }}
                                            {{ Anchor::Print(['text'=>'设置','class'=>'btn-edit-building','href'=>route('school_manager.attendance.timeslots.edit',['id'=>$task->id])], Button::TYPE_DEFAULT,'edit') }}
                                            {{ Anchor::Print(['text'=>'分配','class'=>'btn-edit-building','href'=>route('school_manager.attendance.person.search',['id'=>$task->id])], Button::TYPE_DEFAULT,'edit') }}
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
