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
                    <header>{{ session('school.name') }} 项目管理</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>创建时间</th>
                                    <th>学校</th>
                                    <th>发起人</th>
                                    <th>参与者</th>
                                    <th>任务</th>
                                    <th>标题</th>
                                    <th>状态</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($projects as $index=>$project)
                                    <tr>
                                        <td>{{ _printDate($project->created_at) }}</td>
                                        <td>
                                            {{ $project->school->name }}
                                        </td>
                                        <td>
                                            {{ $project->user->name }}
                                        </td>
                                        <td>
                                            {{ count($project->members) }}人
                                        </td>
                                        <td>
                                            <a href="{{ route('school_manager.oa.tasks-manager',['project_id'=>$project->id]) }}">
                                                {{ count($project->tasks) }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $project->title }}
                                        </td>
                                        <td>
                                            {{ $project->status === \App\Models\OA\Project::STATUS_IN_PROGRESS ? '进行中': '完成' }}
                                        </td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'查看','class'=>'btn-edit-major','href'=>route('school_manager.oa.project-view',['uuid'=>$project->id])], Button::TYPE_DEFAULT,'edit') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{ $projects->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
