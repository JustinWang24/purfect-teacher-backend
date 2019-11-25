<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-3">
            <div class="card">
                <div class="card-head">
                    <header>
                        任务详情: {{ $projectTask->title }}
                    </header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>发布时间</td>
                                        <td>{{ _printDate($projectTask->created_at) }}</td>
                                    </tr>
                                    <tr>
                                        <td>发起人</td>
                                        <td>
                                            {{ $projectTask->user->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>详情</td>
                                        <td>
                                            {{ $projectTask->content }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>状态</td>
                                        <td>
                                            {{ $projectTask->status === \App\Models\OA\Project::STATUS_IN_PROGRESS ? '进行中': '完成' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-head">
                    <header>讨论</header>
                </div>
                <div class="card-body ">
                    <ul class="feedBody">
                        @foreach($projectTask->discussions as $discussion)
                        <li class="active-feed">
                            <div class="feed-user-img">
                                <img src="{{ $discussion->user->profile->avatar }}" class="img-radius " alt="User-Profile-Image">
                            </div>
                            <h6>
                                {{ $discussion->user->name }} <small class="text-muted">{{ _printDate($discussion->created_at) }}</small>
                            </h6>
                            <p class="m-b-15 m-t-15">
                                {{ $discussion->content }}
                            </p>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="card">
                <div class="card-head">
                    <header>
                        <a href="{{ route('school_manager.oa.tasks-manager',['project_id'=>$project->id]) }}">项目详情: "{{ $project->title}}"</a>
                    </header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>发布时间</td>
                                    <td>{{ _printDate($project->created_at) }}</td>
                                </tr>
                                <tr>
                                    <td>发起人</td>
                                    <td>
                                        {{ $project->user->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>标题</td>
                                    <td>
                                        {{ $project->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>状态</td>
                                    <td>
                                        {{ $project->status === \App\Models\OA\Project::STATUS_IN_PROGRESS ? '进行中': '完成' }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
