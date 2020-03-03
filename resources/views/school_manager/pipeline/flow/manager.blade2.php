@extends('layouts.app')
@section('content')
<div class="row" id="pipeline-flows-manager-app">
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card">
            <div class="card-body">
                @foreach($groupedFlows as $item)
                <div class="row mb-4">
                    <div class="col-12">
                        <h4 class="text-primary">
                            <b>{{ $item['name'] }}</b>
                            <el-button type="primary" size="mini" @click="createNewFlow({{ $item['key'] }})" icon="el-icon-plus" class="pull-right">
                                新流程
                            </el-button>
                        </h4>
                        <el-divider></el-divider>
                        <div class="row">
                            @foreach($item['flows'] as $flow)
                            <div class="col-4 mb-4 flow-box" v-on:click="loadFlowNodes({{ $flow->id }},'{{ $flow->name }}')">
                                <img src="{{ $flow->icon }}" width="50">

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

                    <el-button v-if="flowNodes.length > 0" type="danger" size="mini" v-on:click="deleteFlow" icon="el-icon-delete" class="pull-right ml-2">
                    </el-button>

                    <el-button v-if="flowNodes.length > 0" type="primary" size="mini" v-on:click="createNewNode" icon="el-icon-plus" class="pull-right">
                        新步骤
                    </el-button>
                </header>
            </div>
            <div class="card-body">
                <div class="row">
                    <el-timeline class="col-12">
                        <el-timeline-item v-for="(node, index) in flowNodes" :key="index" :timestamp="timelineItemTitle(index, node)" placement="top" color="green">
                            <el-card shadow="hover">
                                <p class="pull-right">
                                    <el-button-group>
                                        <el-button icon="el-icon-plus" size="mini" @click="editNodeOptions(node)">必填项目</el-button>
                                        <el-button icon="el-icon-edit" size="mini" @click="editNode(node)">编辑</el-button>
                                        <el-button v-if="index > 0" icon="el-icon-delete" size="mini" type="danger" @click="deleteNode(index, node)"></el-button>
                                    </el-button-group>
                                </p>
                                <h5><b>负责发起: </b></h5>
                                <p class="pl-4" v-if="node && node.handler && node.handler.organizations && node.handler.organizations.length > 0">
                                    <span class="text-primary"><b>部门: </b></span>
                                    <span class="mr-2" v-for="dept in node.handler.organizations.substring(0,node.handler.organizations.length -1).split(';')">
                                        <el-tag effect="plain" type="info" size="mini">
                                            @{{ dept }}
                                        </el-tag>
                                    </span>
                                </p>
                                <p class="pl-4" v-if="node && node.handler && node.handler.titles && node.handler.titles.length > 0">
                                    <span class="text-primary"><b>成员角色: </b></span>
                                    <span v-for="title in node.handler.titles.substring(0,node.handler.titles.length -1).split(';')" class="mr-2">
                                        <el-tag effect="plain" type="info" size="mini">
                                            @{{ title }}
                                        </el-tag>
                                    </span>
                                </p>
                                <p class="pl-4" v-if="node && node.handler && node.handler.role_slugs && node.handler.role_slugs.length > 0">
                                    <span class="text-primary"><b>用户组: </b></span>
                                    <span v-for="slugTxt in node.handler.role_slugs.substring(0,node.handler.role_slugs.length -1).split(';')" class="mr-2">
                                        <el-tag effect="plain" type="info" size="mini">
                                            @{{ slugTxt }}
                                        </el-tag>
                                    </span>
                                </p>

                                <h5><b>负责审核:</b></h5>
                                <p class="pl-4" v-if="node && node.handler && node.handler.notice_to && node.handler.notice_to.length === 0">
                                    <span class="text-danger">无需下一步审核</span>
                                </p>
                                <p class="pl-4" v-if="node && node.handler && node.handler.notice_to && node.handler.notice_to.length > 0">
                                    <span class="text-primary"><b>角色: </b></span>
                                    <span v-for="notice in node.handler.notice_to.substring(0,node.handler.notice_to.length -1).split(';')" class="mr-2">
                                        <el-tag effect="plain" type="info" size="mini">
                                            @{{ notice }}
                                        </el-tag>
                                    </span>
                                </p>
                                <el-divider></el-divider>
                                <p v-if="node && node.options && node.options.length > 0" style="margin-bottom: 0;">
                                    <span><b>必填信息: </b></span>
                                    <el-button :key="idx" v-for="(op, idx) in node.options" type="text" @click="editNodeOptions(node, op)"><b>@{{ op.name }}</b>;</el-button>
                                </p>

                                <p v-if="node && node.attachments && node.attachments.length > 0" style="margin-bottom: 0;">
                                    <span class="text-primary"><b>关联的附件: </b></span>
                                </p>
                                <ul style="padding-left: 10px;list-style: none;" v-if="node && node.attachments && node.attachments.length > 0">
                                    <li v-for="(at, idx) in node.attachments" :key="idx">
                                        <p style="margin-bottom: 0;">
                                            <a :href="at.url" target="_blank">
                                                <span style="color: #0c0c0c;">@{{ idx+1 }}: @{{ at.file_name }}</span>
                                            </a>&nbsp;
                                            <el-button v-on:click="dropAttachment(idx, at, index)" type="text" style="color: red">删除</el-button>
                                        </p>
                                    </li>
                                </ul>
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

    <el-drawer title="流程管理" size="90%" :visible.sync="flowFormFlag">
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
                        <el-button type="primary" icon="el-icon-picture-outline-round" v-on:click="iconSelectorShowFlag=true">选择图标</el-button>
                        <span v-if="selectedImgUrl" class="ml-4">
                            <img :src="selectedImgUrl" width="50">
                        </span>
                    </el-form-item>
                </el-col>
            </el-row>

            <el-divider></el-divider>
            <h5 class="text-center text-danger">可以使用本流程的用户群体, 请在以下用户群中二选一, 部门+角色的组合优先 </h5>
            @include('school_manager.pipeline.flow.node_form')
            <el-form-item>
                <el-button type="primary" @click="onNewFlowSubmit">立即创建</el-button>
                <el-button @click="flowFormFlag = false">取消</el-button>
            </el-form-item>
        </el-form>

        <el-dialog width="30%" title="选择图标" :visible.sync="iconSelectorShowFlag" append-to-body>
            <icon-selector v-on:icon-selected="iconSelectedHandler"></icon-selector>
        </el-dialog>
    </el-drawer>

    <el-drawer title="流程步骤管理" size="80%" :visible.sync="nodeFormFlag">
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
                            <el-option v-for="(n, idx) in flowNodes" :label="timelineItemTitle(idx, n)" :value="n.id" :key="idx">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-divider></el-divider>
            <h5 class="text-center text-danger">可以操作此步骤的用户群体, 请在以下用户群中二选一, 部门+角色的组合优先 </h5>
            @include('school_manager.pipeline.flow.node_form')
            <el-form-item>
                <el-button type="primary" @click="onNodeFormSubmit">保存</el-button>
                <el-button @click="nodeFormFlag = false">取消</el-button>
            </el-form-item>
        </el-form>
    </el-drawer>

    <el-drawer title="流程步骤必填项管理" size="50%" :visible.sync="nodeOptionsFormFlag">
        <div style="overflow :auto;height: 800px;">
            <el-form ref="currentNodeOptionForm" :model="nodeOption" label-width="120px" style="padding: 10px;">
                <el-form-item label="名称">
                    <el-input v-model="nodeOption.name" placeholder="必填: 例如出差目的地" style="width: 90%;"></el-input>
                </el-form-item>
                <el-form-item label="类型">
                    <el-select v-model="nodeOption.type" placeholder="必填: 请选择数据类型" style="width: 90%;">
                        <el-option v-for="(ot, idx) in nodeOptionTypes" :label="ot" :value="ot" :key="idx">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="onNodeOptionFormSubmit">保存</el-button>
                    <el-button @click="nodeOptionsFormFlag = false">取消</el-button>
                </el-form-item>
                <el-divider></el-divider>
                <el-row>
                    <el-col :span="24">
                        <h5 class="text-info" v-if="node.options.length===0">还没有添加此步骤必填项</h5>
                        <h4 class="text-primary" v-if="node.options.length>0">已有的必填项</h4>
                        <ol>
                            <li v-for="(existOption, idx) in node.options" :key="idx">
                                <span class="text-primary">名称:</span> @{{ existOption.name }}; <span class="text-primary">类型:</span> @{{ existOption.type }}
                                &nbsp;|
                                <el-button type="text" class="text-primary" v-on:click="editNodeOption(existOption)">修改</el-button>
                                <el-button type="text" class="text-danger" v-on:click="removeNodeOption(existOption, idx)">删除</el-button>
                            </li>
                        </ol>
                    </el-col>
                </el-row>
            </el-form>
        </div>
    </el-drawer>

    @include(
    'reusable_elements.section.file_manager_component',
    ['pickFileHandler'=>'pickFileHandler']
    )
</div>
<div id="app-init-data-holder" data-school="{{ session('school.id') }}" data-newflow="{{ $lastNewFlow }}"></div>
@endsection