@php
    use App\Utils\UI\Anchor;
    use App\Utils\UI\Button;
    use App\User;
    /**
     * @var \App\Models\Pipeline\Flow\Node $node
     */
@endphp
@extends('layouts.app')
@section('content')
    <div class="row" id="pipeline-flow-open-app">
        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-head">
                    <header>{{ $node->getName() }} <i class="el-icon-loading" v-if="isLoading"></i> </header>
                </div>
                <div class="card-body">
                    <el-form v-if="!done" ref="startActionForm" :model="action" label-width="120px" style="padding: 10px;">
                        <el-form-item label="原因说明">
                            <el-input type="textarea" placeholder="必填: 请写明原因" rows="6" v-model="action.content"></el-input>
                        </el-form-item>
                        <el-form-item label="申请加急">
                            <el-switch v-model="action.urgent"></el-switch>
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
                            <el-button type="primary" @click="onStartActionSubmit">发起</el-button>
                            <el-button @click="flowFormFlag = false">取消</el-button>
                        </el-form-item>
                    </el-form>

                    <p v-if="done" class="text-primary">
                        提交成功!
                        <el-button size="mini" class="pull-right" v-on:click="closeWindow" type="danger" icon="el-icon-close"> 关闭窗口 </el-button>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-head">
                    <header>说明</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                        <p>{{ $node->description }}</p>
                        @if(count($node->attachments) > 0)
                            <p>附件列表:</p>
                        @foreach($node->attachments as $attachment)
                            <p><a href="{{ $attachment->url }}" target="_blank">{{ $attachment->file }}</a></p>
                        @endforeach
                        @else
                            <p class="text-info">本步骤没有提供附件给申请人</p>
                        @endif
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
         data-nodeid="{{ $node->id }}"
         data-flowid="{{ $flow->id }}"
    ></div>
@endsection
