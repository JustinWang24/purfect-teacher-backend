@extends('layouts.app')
@section('content')
<div class="row" id="pipeline-flows-manager-app">
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card">
            <div class="manger-card-header">
                <h4 class="card-header-top">
                    <span @click="getList(1)" :style="{'color': posiType === 1 ? '#409EFF' :'#313B4C'}">办公审批</span>
                    <el-divider direction="vertical" class="card-header-top-vertical"></el-divider>
                    <span @click="getList(2)" :style="{'color': posiType === 2 ? '#409EFF' :'#313B4C'}">办事大厅</span>
                    <el-divider direction="vertical" class="card-header-top-vertical"></el-divider>
                    <span @click="getList(3)" :style="{'color': posiType === 3 ? '#409EFF' :'#313B4C'}">系统流程</span>
                </h4>
                <el-divider></el-divider>
            </div>
            <div class="card-body" v-for="item in typeList" :key="item.key">
                <div class="row mb-4">
                    <div class="col-12">
                        <h4 style="font-weight: 600;">
                            <b>@{{ item.name }}</b>
                            <el-button type="primary" size="mini" @click="createNewFlow()" icon="el-icon-plus" class="pull-right">
                                新增流程
                            </el-button>
                        </h4>
                        <el-divider></el-divider>
                        <div class="row">
                            <div class="col-4 mb-4 flow-box" v-if="item.flows" v-for="i in item.flows" @click="loadFlowNodes(i.id, i.name)">
                                <img :src="i.icon" width="50">
                                <el-badge value="新" class="item" v-if="lastNewFlow">
                                    @{{ i.name }}
                                </el-badge>
                                <span v-else>@{{ i.name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card">
            <div class="card-head">
                <header class="full-width">
                    <span class="pull-left pt-2">流程: @{{ flow.name }} <i class="el-icon-loading" v-if="loadingNodes"></i></span>
                    <el-button type="danger" size="mini" v-on:click="deleteFlow" icon="el-icon-delete" class="pull-right ml-2" v-if="juese.length > 0"></el-button>
                </header>
            </div>
            <div class="card-body" v-if="juese.length > 0">
                <div class="row">
                    <el-timeline class="col-12">
                        <el-timeline-item timestamp="发起审批流程部门/人员" placement="top" color="green">
                            <p class="pull-right">
                                <el-button-group>
                                    <el-button icon="el-icon-plus" size="mini" @click="option" ref="option" href="{{ route('school_manager.pipeline.flows-option') }}">自定义表单</el-button>
                                    <el-button icon="el-icon-edit" size="mini" @click="editFlow">编辑</el-button>
                                </el-button-group>
                            </p>
                            <p v-if="zuzhi.length > 0">
                                <span class="text-primary"><b>部门: </b></span>
                                <span class="mr-2" v-for="dept in zuzhi.substring(0,zuzhi.length -1).split(';')">
                                    <el-tag effect="plain" type="info" size="mini">
                                        @{{ dept }}
                                    </el-tag>
                                </span>
                            </p>
                            <p v-if="juese.length > 0">
                                <span class="text-primary"><b>角色: </b></span>
                                <span v-for="title in juese.substring(0,juese.length -1).split(';')" class="mr-2">
                                    <el-tag effect="plain" type="info" size="mini">
                                        @{{ title }}
                                    </el-tag>
                                </span>
                            </p>
                        </el-timeline-item>

                        <el-timeline-item timestamp="审批人" placement="top">
                            <p class="pull-right">
                                <el-button-group>
                                    <el-button size="mini" @click="approver" ref="approver" href="{{ route('school_manager.pipeline.flows-handler') }}">设置审批人</el-button>
                                </el-button-group>
                            </p>
                            <p v-if="handler.length > 0" v-for="(item,index) in handler" :key="item.id">
                                第@{{ index + 1}}步：@{{ item.titles }}
                            </p>
                        </el-timeline-item>
                        <el-timeline-item timestamp="抄送人" placement="top">
                            <p class="pull-right">
                                <el-button-group>
                                    <el-button size="mini" @click="approver" ref="approver" href="{{ route('school_manager.pipeline.flows-handler') }}">设置抄送人</el-button>
                                </el-button-group>
                            </p>
                            <p v-if="copy.length > 0" v-for="(item,index) in copy" :key="item.user_id">
                                @{{ item.name }}
                            </p>
                        </el-timeline-item>
                    </el-timeline>
                </div>
            </div>
        </div>
    </div>

    <el-drawer title="添加/修改流程" size="70%" :visible.sync="flowFormFlag">
        <el-form ref="currentFlowForm" label-width="120px" style="padding: 10px;">
            <el-row>
                <el-col :span="7">
                    <el-form-item label="显示位置">
                        <el-select v-model="posiType" placeholder="请选择显示位置" @change="changeItem1(posiType)">
                            <el-option v-for="item in posiList" :key="item.key" :label="item.name" :value="item.key"></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="7">
                    <el-form-item label="流程分类">
                        <el-select v-model="flow.type" placeholder="请选择流程分类">
                            <el-option v-for="item in typeList" :key="item.key" :label="item.name" :value="item.key"></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="7">
                    <el-form-item label="流程名称">
                        <el-input v-model="flow.name" placeholder="必填: 流程名称"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="10">
                    <el-form-item label="选择图标">
                        <el-button type="primary" icon="el-icon-picture-outline-round" @click="iconSelectorShowFlag=true">选择图标</el-button>
                        <span v-if="selectedImgUrl" class="ml-4">
                            <img :src="selectedImgUrl" width="50">
                        </span>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row v-if="posiType === 3">
                <el-col :span="7">
                    <el-form-item label="关联业务">
                        <el-select v-model="flow.business" placeholder="请选择关联业务" @change="getbusinessList">
                            <el-option v-for="item in businessList" :key="item.business" :label="item.name" :value="item.business"></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-divider></el-divider>
            <h4 style="margin-left: 30px; font-weight: 600;">使用人员</h4>
            <el-row>
                <el-col :span="18">
                    <el-form-item label="目标用户">
                        <el-checkbox-group v-model="node.handlers" v-if="posiType == 1">
                            <el-checkbox label="教师"></el-checkbox>
                            <el-checkbox label="职工"></el-checkbox>
                            <el-checkbox label="学生" disabled></el-checkbox>
                        </el-checkbox-group>
                        <el-checkbox-group v-model="node.handlers" v-if="posiType == 2">
                            <el-checkbox label="教师" disabled></el-checkbox>
                            <el-checkbox label="职工" disabled></el-checkbox>
                            <el-checkbox label="学生"></el-checkbox>
                        </el-checkbox-group>
                        <el-checkbox-group v-model="node.handlers" v-if="posiType == 3">
                            <el-checkbox label="教师"></el-checkbox>
                            <el-checkbox label="职工"></el-checkbox>
                            <el-checkbox label="学生"></el-checkbox>
                        </el-checkbox-group>
                    </el-form-item>
                    <el-form-item label="组织" v-if="posiType !== 2">
                        <el-select v-model="organization" placeholder="请选择组织分类" @change="changeItem2(organization)">
                            <el-option v-for="item in organizationList" :key="item.key" :label="item.name" :value="item.key"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="部门" v-if="organization == 1&&posiType !== 2">
                        <el-cascader style="width: 90%;" :props="props" v-model="node.organizations"></el-cascader>
                    </el-form-item>
                    <el-form-item label="角色">
                        <el-checkbox-group v-model="node.titles">
                            <el-checkbox v-for="item in titlesList" :label="item" :key="item">@{{item}}</el-checkbox>
                        </el-checkbox-group>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-form-item>
                <el-button type="primary" @click="onNewFlowSubmit">立即创建</el-button>
                <el-button @click="flowFormFlag = false">取消</el-button>
            </el-form-item>
        </el-form>
        <el-dialog width="30%" title="选择图标" :visible.sync="iconSelectorShowFlag" append-to-body>
            <icon-selector v-on:icon-selected="iconSelectedHandler"></icon-selector>
        </el-dialog>
    </el-drawer>

</div>
<div id="app-init-data-holder" data-school="{{ session('school.id') }}" data-newflow="{{ $lastNewFlow }}"></div>
@endsection