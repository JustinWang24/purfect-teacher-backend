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
                    <header>
                        项目: "{{ $project->title}}" - 任务管理
                        <a class="btn btn-primary btn-sm" href="{{ route('school_manager.oa.projects-manager',['uuid'=>session('school.uuid')]) }}">返回项目列表</a>
                    </header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>创建时间</th>
                                    <th>发起人</th>
                                    <th>标题</th>
                                    <th>详情</th>
                                    <th>状态</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tasks as $index=>$task)
                                    <tr>
                                        <td>{{ _printDate($task->created_at) }}</td>
                                        <td>
                                            {{ $task->user->name }}
                                        </td>
                                        <td>
                                            {{ $task->title }}
                                        </td>
                                        <td>
                                            {{ $task->content }}
                                        </td>
                                        <td>
                                            {{ $task->status === \App\Models\OA\Project::STATUS_IN_PROGRESS ? '进行中': '完成' }}
                                        </td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'查看','class'=>'btn-edit-major','href'=>route('school_manager.oa.task-view',['task_id'=>$task->id])], Button::TYPE_DEFAULT,'edit') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $tasks->appends($appendedParams)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
