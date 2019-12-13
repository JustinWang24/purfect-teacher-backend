@extends('layouts.h5_app')
@section('content')
<div id="app-init-data-holder" data-flowid="{{ $flow->id }}" data-nodeid="{{ $node->id }}" data-school="{{ $user->getSchoolId() }}" data-apprequest="1"></div>
<div id="{{ $appName }}" class="school-intro-container">
    <div class="header">
        <h2 class="title">{{ $flow->name }} <i class="el-icon-loading" v-if="isLoading"></i></h2>
    </div>
    <div class="main p-15">
        <h5>申请人: {{ $user->name }}</h5>
        <el-form v-if="!done" ref="startActionForm" :model="action" label-width="80px">
            <el-form-item label="原因说明" class="nb">
                <el-input type="textarea" placeholder="必填: 请写明原因" rows="6" v-model="action.content"></el-input>
            </el-form-item>
            <el-form-item label="申请加急" class="nb">
                <el-switch v-model="action.urgent"></el-switch>
            </el-form-item>
            <el-form-item label="选择附件" class="nb">
                <el-button type="primary" size="mini" icon="el-icon-document" v-on:click="showFileManagerFlag=true">选择附件</el-button>
                <ul style="padding-left: 0;">
                    <li v-for="(a, idx) in action.attachments" :key="idx">
                        <p style="margin-bottom: 0;">
                            <span>@{{ a.file_name }}</span>&nbsp;<el-button v-on:click="dropAttachment(idx, a)" type="text" style="color: red">删除</el-button>
                        </p>
                    </li>
                </ul>
            </el-form-item>

            <p v-if="done" class="text-primary">
                提交成功!
            </p>

            <p>说明: {{ $node->description }}</p>
            @if(count($node->attachments) > 0)
                <p>附件列表:</p>
                @foreach($node->attachments as $attachment)
                    <p><a href="{{ $attachment->url }}" target="_blank">{{ $attachment->file }}</a></p>
                @endforeach
            @else
                <p class="text-info">本步骤没有提供附件给申请人</p>
            @endif
        </el-form>

        <h5>审批流程</h5>

        <el-timeline>
            @foreach($flow->nodes as $key=>$n)
            <el-timeline-item
                    key="{{ $key }}">
                {{ $n->name }}
            </el-timeline-item>
            @endforeach
        </el-timeline>

        <el-button class="full-width" type="primary" @click="onStartActionSubmit">发起</el-button>
    </div>
    @include(
        'reusable_elements.section.file_manager_component_mobile',
        ['pickFileHandler'=>'pickFileHandler']
    )
</div>
@endsection
