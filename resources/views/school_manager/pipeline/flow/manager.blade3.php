@extends('layouts.app')
@section('content')
<div class="row" id="pipeline-flows-manager-app">
    <div class="col-sm-12 col-md-3 col-lg-3 col-xl-4">
        <div class="card">
            <div class="card-head">
                <header>设置审批人</header>
            </div>
            <div class="card-body">
                <div class="card-body-approver">
                    <div style="padding: 5px;background-color: yellow;">
                        <img src="{{asset('assets/img/teacher_blade/qingjia@2x.png')}}" alt="">
                        <span>&nbsp;&nbsp;&nbsp;审批人</span>
                        <span> （一级审批）</span>
                    </div>
                    <div>
                        请选择审批人
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-9 col-lg-9 col-xl-8">
        <div class="card">
            <div class="card-head">
                <header>选择审批人</header>
            </div>
            <div class="card-body">
                <el-form style="margin-top: 20px">
                    <el-form-item label="组织">
                        <el-select v-model="organization" placeholder="请选择组织分类" style="width: 90%;" @change="changeItem2(organization)">
                            <el-option v-for="item in organizationList" :key="item.key" :label="item.name" :value="item.key"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="部门" v-if="organization === 1">
                        <el-cascader style="width: 90%;" :props="props" v-model="node.organizations"></el-cascader>
                    </el-form-item>
                    <el-form-item label="审批人">
                        <el-radio-group v-model="approver">
                            <el-radio v-for="item in titlesList" :label="item" :key="item">@{{item}}</el-radio>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item style="display: flex;justify-content: center;">
                        <el-button type="primary" style="width: 140px; height: 37px;">确定</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>
        <div class="card">
            <div class="card-head">
                <header>选择抄送人</header>
            </div>
            <div class="card-body" style="margin-top: 20px;">
                <el-autocomplete v-model="teacher" prefix-icon="el-icon-search" placeholder="请输入教职工名字"></el-autocomplete>
            </div>
        </div>
    </div>
</div>
<div id="app-init-data-holder" data-school="{{ session('school.id') }}" data-newflow="{{ $lastNewFlow }}"></div>
@endsection