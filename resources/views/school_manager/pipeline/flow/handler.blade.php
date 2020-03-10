@extends('layouts.app')
@section('content')
<div class="row" id="pipeline-flows-manager-app">
    <div class="col-sm-12 col-md-3 col-lg-3 col-xl-4">
        <div class="card">
            <div class="card-head">
                <header>设置审批人</header>
            </div>
            <div class="card-body">
                <p style="text-align: center;margin-bottom: 20px;">流程开始</p>
                <img src="{{asset('assets/img/pipeline/addTo@2x.png')}}" alt="" @click="show1 = !show1" style="position: relative;left: 45%;vertical-align: baseline;">
                <div class="card-body-approver" v-for="(item,index) in handler" :key="item.id">
                    <div style="padding: 5px;background-color: #FE7B1C;">
                        <img src="{{asset('assets/img/teacher_blade/qingjia@2x.png')}}" alt="" class="portrait">
                        <span>&nbsp;&nbsp;&nbsp;审批人</span>
                        <span> （@{{ index + 1 }}级审批）</span>
                    </div>
                    <div style="border: 1px solid #FE7B1C; color: #313B4C; padding: 10px 6px;">
                        <p>@{{ item .titles }}</p>
                    </div>
                    <img src="{{asset('assets/img/pipeline/addTo@2x.png')}}" alt="" @click="prev(item.node_id)" style="position: relative;left: 45%;vertical-align: baseline;margin-top: 10px">
                </div>

                <div class="card-body-approver">
                    <div style="padding: 5px;background-color: #4EA5FE">
                        <img src="{{asset('assets/img/teacher_blade/qingjia@2x.png')}}" alt="" class="portrait">
                        <span>&nbsp;&nbsp;&nbsp;抄送人</span>
                    </div>
                    <div style="border: 1px solid #4EA5FE; color: #313B4C; padding: 10px 6px;" @click="show2 = !show2">
                        <p v-if="copy.length == 0">请选择抄送人</p>
                        <p v-for="(item,index) in copy" :key="item.user_id" v-else>@{{ item.name }}</p>
                    </div>
                </div>
                <p style="text-align: center;margin: 30px 0;">流程结束</p>
                <div style="display: flex;justify-content: space-around;align-items: center;margin-top: 30px;">
                    <p>审批自动同意</p>
                    <el-select v-model="agree">
                        <el-option v-for="item in agreeList" :key="item.key" :label="item.name" :value="item.key"></el-option>
                    </el-select>
                </div>
                <el-button type="primary" style="width: 100px;margin-top: 60px;margin-left: 35%;" @click="saveagree">保存</el-button>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-9 col-lg-9 col-xl-8">
        <div class="card" v-if="show1">
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
                        <el-radio-group v-model="node.titles">
                            <el-radio v-for="item in titlesList" :label="item" :key="item">@{{item}}</el-radio>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item style="display: flex;justify-content: center;">
                        <el-button type="primary" style="width: 140px; height: 37px;" @click="setone">确定</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>
        <div class="card" v-if="show2">
            <div class="card-head">
                <header>选择抄送人</header>
            </div>
            <div class="card-body" style="margin-top: 20px;">
                <search-bar
                    :school-id="{{ session('school.id') }}"
                    full-tip="输入教职工名字"
                    scope="employee"
                    class="ml-4"
                    :init-query="currentMember.name"
                    v-on:result-item-selected="selectMember"
                ></search-bar>
                <el-tag
                        :key="idx"
                        v-for="(member, idx) in members"
                        class="mr-2"
                        closable
                        :disable-transitions="false"
                        @click="editMember(member)"
                        @close="removeFromOrg(member)">
                    @{{  member.title }}: @{{ member.name }}
                </el-tag>
                <!-- <search-bar :school-id="{{ session('school.id') }}" full-tip="输入教职工名字" scope="employee" class="ml-4" :init-query="teacher.name" v-on:result-item-selected="selectMember"></search-bar>
                <el-tag :key="idx" v-for="(member, idx) in members" class="mr-2" closable :disable-transitions="false" @close="removeFromOrg(member)">
                    @{{ member }}
                </el-tag> -->
            </div>
            <el-button type="primary" style="width: 140px; height: 37px; margin: 20px auto;" @click="savecopy">确定</el-button>
        </div>
    </div>
</div>
<div id="app-init-data-holder" data-school="{{ session('school.id') }}" data-newflow="{{ $flow->id }}"></div>
@endsection