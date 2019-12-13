@php
    $flowStillInProgress = $userFlow->done === \App\Utils\Pipeline\IUserFlow::IN_PROGRESS;
@endphp
@extends('layouts.h5_app')
@section('content')
    <div id="app-init-data-holder"
         data-school="{{ $user->getSchoolId() }}"
         data-useruuid="{{ $user->uuid }}"
         data-apitoken="{{ $user->api_token }}"
         data-flowid="{{ $userFlow->id }}"
         data-actionid="{{ $action ? $action->id : null }}"
         data-theaction="{{ $action }}"
         data-apprequest="1"></div>
    <div id="pipeline-flow-view-history-app" class="school-intro-container">
        <div class="header">
            <h2 class="title">{{ $pageTitle }} </h2>
        </div>
        <div class="main" v-if="isLoading">
            <p class="text-center text-grey">
                <i class="el-icon-loading"></i>&nbsp;数据加载中 ...
            </p>
        </div>
        <div class="main p-15" v-show="!isLoading">
            <header class="full-width">
                <p class="pt-2">
                    申请人: {{ $userFlow->user_name }}
                    审批状态:
                    <span class="pull-right">
                            @if($flowStillInProgress)
                            <el-tag>处理中 ...</el-tag>
                        @elseif($userFlow->done === \App\Utils\Pipeline\IUserFlow::DONE)
                            <el-tag type="success"><i class="el-icon-check"></i>&nbsp;批准</el-tag>
                        @else
                            <el-tag type="danger">驳回</el-tag>
                        @endif
                    </span>
                </p>
            </header>

            <h5>审批流程</h5>
            <div class="user-view-flow-history-steps">
                <el-timeline :reverse="false">
                    <el-timeline-item
                            v-for="(node, index) in history"
                            :key="index"
                            :color="getDotColor(node, {{ $node->id }}, {{ $userFlow->done }})"
                            :timestamp="node.name" placement="top">
                        <node-mobile :node="node" :starter="index===0" :highlight="node.id === {{ $node->id }} && {{ $flowStillInProgress ? 'true' : 'false' }}"></node-mobile>
                    </el-timeline-item>
                    @if($flowStillInProgress)
                        <el-timeline-item v-if="!isLoading" icon="el-icon-more" color="#409EFF" timestamp="审核中 ..." placement="top">&nbsp;</el-timeline-item>
                    @elseif($userFlow->done === \App\Utils\Pipeline\IUserFlow::DONE)
                        <el-timeline-item v-if="!isLoading" icon="el-icon-check" color="#0bbd87" timestamp="批准" placement="top">&nbsp;</el-timeline-item>
                    @else
                        <el-timeline-item v-if="!isLoading" icon="el-icon-close" color="#F56C6C" timestamp="驳回" placement="top">&nbsp;</el-timeline-item>
                    @endif

                </el-timeline>
            </div>

            <a style="display: block; color: white;text-decoration: none;text-align: center;" href="{{ route('h5.flow.user.in-progress',['api_token'=>$api_token]) }}" class="showMoreButton">返回</a>
        </div>
    </div>
@endsection
