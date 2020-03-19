@extends('layouts.h5_app')
@section('content')
<div id="app-init-data-holder" data-school="{{ $user->getSchoolId() }}" data-useruuid="{{ $user->uuid }}" data-apitoken="{{ $user->api_token }}" data-apprequest="1"></div>
<div id="student-homepage-app" class="school-intro-container">
    <div class="main" v-if="isLoading">
        <p class="text-center text-grey">
            <i class="el-icon-loading"></i>&nbsp;数据加载中 ...
        </p>
    </div>
    <div class="main p-15" v-if="processedList.length > 0">
        <el-input placeholder="搜索标题、发起人关键字" v-model="keyword" style="margin-bottom: 10px;" @input="loadFlowsProcessedByMe"></el-input>
        <div class="pipeline-user-flow-box" v-for="(userFlow, idx) in processedList" :key="idx" @click="viewMyApplication(userFlow)">
            <el-card shadow="hover" class="pb-3">
                <div style="display: flex;align-items: center;">
                    <img :src="userFlow.avatar" style="border-radius: 50%;width: 40px;height: 40px;">
                    <h3 style="margin-left: 20px;flex: 4;">
                        <p style="line-height: 0;display: flex;justify-content: space-between;">
                            <span>@{{ userFlow.user_name }}的@{{ userFlow.flow.name }}申请</span>
                            <span style="font-weight: 100;font-size: 16px;color: #FE7B1C" v-if="@{{  userFlow.done === 0 }}">审批中</span>
                            <span style="font-weight: 100;font-size: 16px;color: #6DCC58" v-if="@{{  userFlow.done === 1 }}">已通过</span>
                            <span style="font-weight: 100;font-size: 16px;color: #FD1B1B" v-if="@{{  userFlow.done === 2 }}">未通过</span>
                        </p>
                        <p style="font-size: 13px;color: #ABABAB;margin: 0">类型：@{{ userFlow.flow.name }}</p>
                        <time style="font-size: 13px;color: #ABABAB;">@{{ userFlow.created_at.substring(0, 16) }}</time>
                    </h3>
                </div>
            </el-card>
        </div>
    </div>
    <div v-else style="display: flex;flex-direction: column;align-items: center;background-color: #fff;margin-top: 150px;">
        <img src="{{asset('assets/img/pipeline/nothing@2x.png')}}" alt="" style="width: 290px;height: 220px;">
        <p style="color: #6F7275;text-align: center;font-family:PingFangSC-Regular,PingFang SC;">暂无数据哦~</p>
    </div>
</div>
@endsection
