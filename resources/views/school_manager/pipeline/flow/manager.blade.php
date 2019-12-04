@php
    use App\Utils\UI\Anchor;
    use App\Utils\UI\Button;
@endphp
@extends('layouts.app')
@section('content')
    <div class="row" id="pipeline-flows-manager-app">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-body">
                    @foreach($groupedFlows as $type=>$flows)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="text-primary">
                                <b>{{ \App\Models\Pipeline\Flow\Flow::Types()[$type] }}</b>
                                <el-button type="primary" size="mini" @click="createNewFlow({{ $type }})" icon="el-icon-plus" class="pull-right">
                                    新流程
                                </el-button>
                            </h4>
                            <el-divider></el-divider>
                            <div class="row">
                                @foreach($flows as $flow)
                                <div class="col-4 mb-4 flow-box" v-on:click="loadFlowNodes({{ $flow->id }},'{{ $flow->name }}')">
                                    <img src="{{ $flow->getIconUrl() }}" width="50">

                                    @if($lastNewFlow && intval($lastNewFlow) === $flow->id)
                                        <el-badge value="新" class="item">
                                            {{ $flow->name }}
                                        </el-badge>
                                    @else
                                        <span>{{ $flow->name }}</span>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <span class="pull-left pt-2">流程: @{{ currentFlow.name }} <i class="el-icon-loading" v-if="loadingNodes"></i></span>
                        <el-button
                                v-if="flowNodes.length > 0"
                                type="primary"
                                size="mini"
                                v-on:click="createNewNode"
                                icon="el-icon-plus"
                                class="pull-right">
                            新步骤
                        </el-button>
                    </header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <el-timeline class="col-12">
                            <el-timeline-item
                                    v-for="(node, index) in flowNodes"
                                    :key="index"
                                    :timestamp="timelineItemTitle(index, node)" placement="top" color="green">
                                <el-card>
                                    <p class="pull-right">
                                        <el-button icon="el-icon-edit" size="mini" type="primary" @click="editNode(node)"></el-button>
                                        <el-button v-if="index > 0" icon="el-icon-delete" size="mini" type="danger" @click="deleteNode(index, node)"></el-button>
                                    </p>
                                    <p v-if="node.handler.organizations.length > 0">
                                        <span class="text-primary"><b>部门: </b></span>
                                        <span class="mr-2" v-for="dept in node.handler.organizations.substring(0,node.handler.organizations.length -1).split(';')">
                                            <el-tag effect="plain" type="info" size="mini">
                                                @{{ dept }}
                                            </el-tag>
                                        </span>
                                    </p>
                                    <p v-if="node.handler.titles.length > 0">
                                        <span class="text-primary"><b>成员角色: </b></span>
                                        <span v-for="title in node.handler.titles.substring(0,node.handler.titles.length -1).split(';')" class="mr-2">
                                            <el-tag effect="plain" type="info" size="mini">
                                                @{{ title }}
                                            </el-tag>
                                        </span>
                                    </p>
                                    <p v-if="node.handler.role_slugs.length > 0">
                                        <span class="text-primary"><b>用户组: </b></span>
                                        <span v-for="slugTxt in node.handler.role_slugs.substring(0,node.handler.role_slugs.length -1).split(';')" class="mr-2">
                                            <el-tag effect="plain" type="info" size="mini">
                                                @{{ slugTxt }}
                                            </el-tag>
                                        </span>
                                    </p>
                                    <p><span class="text-primary"><b>说明: </b></span>@{{ node.description }}</p>
                                </el-card>
                            </el-timeline-item>

                            <el-timeline-item v-if="flowNodes.length > 1" timestamp="流程结束" placement="top">
                            </el-timeline-item>
                            <el-timeline-item v-if="flowNodes.length === 1" timestamp="流程发起后还没有任何处理步骤, 请继续完善" color="red" placement="top">
                            </el-timeline-item>
                        </el-timeline>
                    </div>
                </div>
            </div>
        </div>

        <el-drawer
                title="流程管理"
                size="80%"
                :visible.sync="flowFormFlag">
            <el-form ref="currentFlowForm" :model="currentFlow" label-width="120px" style="padding: 10px;">
                <el-row>
                    <el-col :span="6">
                        <el-form-item label="分类">
                            <el-select v-model="currentFlow.type" placeholder="请选择流程分类">
                                @foreach(\App\Models\Pipeline\Flow\Flow::Types() as $key=>$v)
                                    <el-option label="{{ $v }}" :value="{{ $key }}"></el-option>
                                @endforeach
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="流程名称">
                            <el-input v-model="currentFlow.name" placeholder="必填: 流程名称"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="10">
                        <el-form-item label="选择图标">
                            <el-button type="primary" icon="el-icon-picture-outline-round" v-on:click="iconSelectorShowFlag=true">系统图标</el-button>
                            <el-button type="primary" icon="el-icon-picture" v-on:click="showFileManagerFlag=true">我的云盘</el-button>
                            <span v-if="selectedImgUrl" class="ml-4">
                                <img :src="selectedImgUrl" width="50">
                            </span>
                        </el-form-item>
                    </el-col>
                </el-row>

                <el-divider></el-divider>
                <h5 class="text-center text-danger">可以使用本流程的用户群体, 请在以下用户群中二选一, 部门+角色的组合优先 </h5>
                <el-divider></el-divider>
                @include('school_manager.pipeline.flow.node_form')
                <el-form-item>
                    <el-button type="primary" @click="onNewFlowSubmit">立即创建</el-button>
                    <el-button @click="flowFormFlag = false">取消</el-button>
                </el-form-item>
            </el-form>

            <el-dialog
                    width="30%"
                    title="选择图标"
                    :visible.sync="iconSelectorShowFlag"
                    append-to-body>
                <icon-selector v-on:icon-selected="iconSelectedHandler"></icon-selector>
            </el-dialog>
        </el-drawer>

        <el-drawer
                title="流程步骤管理"
                size="80%"
                :visible.sync="nodeFormFlag">
            <el-form ref="currentNodeForm" :model="node" label-width="120px" style="padding: 10px;">
                <el-row>
                    <el-col :span="12">
                        <el-form-item label="步骤名称">
                            <el-input v-model="node.name" placeholder="必填: 步骤名称"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="前一步">
                            <el-select v-model="prevNodeId" placeholder="必填: 前一步" style="width: 90%;">
                                <el-option
                                        v-for="(n, idx) in flowNodes"
                                        :label="timelineItemTitle(idx, n)" :value="n.id" :key="idx">
                                </el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-divider></el-divider>
                <h5 class="text-center text-danger">可以操作此步骤的用户群体, 请在以下用户群中二选一, 部门+角色的组合优先 </h5>
                <el-divider></el-divider>
                @include('school_manager.pipeline.flow.node_form')
                <el-form-item>
                    <el-button type="primary" @click="onNodeFormSubmit">保存</el-button>
                    <el-button @click="nodeFormFlag = false">取消</el-button>
                </el-form-item>
            </el-form>
        </el-drawer>

        @include(
                'reusable_elements.section.file_manager_component',
                ['pickFileHandler'=>'pickFileHandler']
            )
    </div>
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-newflow="{{ $lastNewFlow }}"
    ></div>
@endsection
