@php
    use App\Utils\UI\Anchor;
    use App\Utils\UI\Button;
    $flowStillInProgress = $userFlow->done === \App\Utils\Pipeline\IUserFlow::IN_PROGRESS;
@endphp
@extends('layouts.app')
@section('content')
    <div class="row" id="pipeline-flow-view-history-app">
        @if($showActionEditForm)
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card">
                <div class="card-head">
                    <header>{{ $node->name }}</header>
                </div>
                <div class="card-body">
                    <el-form ref="currentActionForm" :model="action" label-width="120px" style="padding: 10px;">
                        <el-form-item label="审核意见">
                            <el-select v-model="action.result" placeholder="请选择您的审核意见">
                                <el-option v-for="(re, idx) in results" :key="idx" :label="re.label" :value="re.id"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="申请加急">
                            <el-switch v-model="action.urgent"></el-switch>
                        </el-form-item>
                        <el-form-item label="原因说明">
                            <el-input type="textarea" placeholder="必填: 请写明原因" rows="6" v-model="action.content"></el-input>
                        </el-form-item>
                        <el-form-item label="选择附件">
                            <el-button type="primary" icon="el-icon-document" v-on:click="showFileManagerFlag=true">选择附件</el-button>
                            <ul style="padding-left: 0;">
                                <li v-for="(a, idx) in action.attachments" :key="idx">
                                    <p style="margin-bottom: 0;">
                                        <span>@{{ a.file_name }}</span>&nbsp;<el-button v-on:click="dropAttachment(idx, a)" type="text" style="color: red">删除</el-button>
                                    </p>
                                </li>
                            </ul>
                        </el-form-item>

                        <el-form-item>
                            <el-button type="primary" @click="onCreateActionSubmit">{{ trans('general.submit') }}</el-button>
                        </el-form-item>
                    </el-form>
                </div>
            </div>
        </div>
        @endif

        <div class="col-lg-{{ $showActionEditForm ? 8 : 12 }} col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <p class="pull-left pt-2">{{ $node->flow->name }}: {{ $action->userFlow->user_name }} <i class="el-icon-loading" v-if="isLoading"></i></p>

                        <span class="pull-right">
                            @if($flowStillInProgress)
                                <el-tag>处理中 ...</el-tag>
                            @elseif($userFlow->done === \App\Utils\Pipeline\IUserFlow::DONE)
                                <el-tag type="success"><i class="el-icon-check"></i>&nbsp;批准</el-tag>
                            @else
                                <el-tag type="danger">驳回</el-tag>
                            @endif
                        </span>
                    </header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <el-timeline :reverse="false">
                                <el-timeline-item
                                        v-for="(node, index) in history"
                                        :key="index"
                                        :color="getDotColor(node, {{ $node->id }}, {{ $userFlow->done }})"
                                        :timestamp="node.name" placement="top">
                                    <node :node="node" :starter="index===0" :highlight="node.id === {{ $node->id }} && {{ $flowStillInProgress ? 'true' : 'false' }}"></node>
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
                    </div>
                </div>
            </div>
        </div>
        @include(
                'reusable_elements.section.file_manager_component',
                ['pickFileHandler'=>'pickFileHandler']
            )
    </div>

    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-actionid="{{ $actionId }}"
         data-flowid="{{ $userFlowId }}"
         data-useruuid="{{ \Illuminate\Support\Facades\Auth::user()->uuid }}"
         data-theaction="{{ $action }}"
    ></div>
@endsection
