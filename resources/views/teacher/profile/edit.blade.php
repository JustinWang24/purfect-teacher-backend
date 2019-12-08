<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-3">
            @include('teacher.elements.sidebar.avatar',['profile'=>$profile])
        </div>
        <div class="col-sm-12 col-md-6 col-xl-6">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <span class="pull-left pt-2">{{ $teacher->name }}履历表</span>
                        <a class="btn btn-primary btn-sm pull-right" href="{{ route('school_manager.teachers.manage-performance',['uuid'=>$teacher->uuid]) }}">
                            年终考评表
                        </a>
                    </header>
                </div>
                <div class="card-body">
                    @foreach($history as $performance)
                    <div class="performance-block">
                        <h4>{{ $performance->year }}年度工作评估: <span class="text-primary">{{ $performance->getResultText() }}</span></h4>
                        <h5>职称: {{ $performance->title }} - 部门: {{ $performance->organisation_name }}</h5>
                        <p class="text">
                            <span><b>评估报告</b></span>: {{ $performance->comments }}
                        </p>
                        <ul>
                        @foreach($performance->items as $item)
                            <li>
                                <div class="performance-item-block">
                                    <p><b>评估项目</b>: {{ $item->config->name }}</p>
                                    <p><b>评估结果</b>: {{ $item->comments }}</p>
                                    <p><b>总评</b>: {{ $item->getResultText() }}</p>
                                </div>
                            </li>
                        @endforeach
                        </ul>
                        <p class="text-right text-primary"><b>评估人</b>: {{ $performance->approvedBy->name }}</p>
                        <hr>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-3 col-xl-3">
            <div class="card">
                <div class="card-head">
                    <header>工作职责</header>
                </div>
                <div class="card-body " id="bar-parent">
                    <p>课程情况</p>
                    <p>提交的申请</p>
                    <p>...</p>
                </div>
            </div>
        </div>
    </div>
@endsection
