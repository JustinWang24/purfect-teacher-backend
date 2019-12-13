@extends('layouts.h5_app')
@section('content')
    <div id="app-init-data-holder" data-school="{{ $user->getSchoolId() }}" data-useruuid="{{ $user->uuid }}" data-apprequest="1"></div>
    <div id="student-homepage-app" class="school-intro-container">
        <div class="header">
            <h2 class="title">我的申请 <i class="el-icon-loading" v-if="isLoading"></i></h2>
        </div>
        <div class="main p-15">
            <div class="pipeline-user-flow-box" v-for="(userFlow, idx) in flowsStartedByMe" :key="idx">
                <el-card shadow="hover" class="pb-3">
                    <h3 style="line-height: 40px;">
                        <img :src="userFlow.flow.icon" width="40">
                        <span class="pull-right">@{{ userFlow.flow.name }}</span>
                    </h3>
                    <el-divider></el-divider>
                    <h5 :class="flowResultClass(userFlow.done)">
                        @{{ flowResultText(userFlow.done) }}
                    </h5>
                    <time class="pull-left" style="font-size: 13px;color: #999;">申请日期: @{{ userFlow.created_at.substring(0, 10) }}</time>
                    <br>
                    <el-divider></el-divider>
                    <div class="clearfix"></div>
                    <el-button @click="viewMyApplication(userFlow)" type="primary" size="mini" class="button pull-left">查看详情</el-button>
                    <el-button v-if="!userFlow.done" @click="cancelMyApplication(userFlow)" type="danger" size="mini" class="button pull-right">撤销</el-button>
                </el-card>
            </div>
        </div>
    </div>
@endsection
