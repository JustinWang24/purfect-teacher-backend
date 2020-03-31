@extends('layouts.h5_app')
@section('content')
<div id="app-init-data-holder" data-position="{{ $position }}" data-school="{{ $user->getSchoolId() }}" data-useruuid="{{ $user->uuid }}" data-apitoken="{{ $user->api_token }}" data-apprequest="1"></div>
<div id="student-homepage-app">
    <div class="main" v-if="isLoading">
        <p class="text-center text-grey">
            <i class="el-icon-loading"></i>&nbsp;数据加载中 ...
        </p>
    </div>
    <div class="main p-15" v-cloak>
        <div>
            <el-input placeholder="搜索标题、发起人关键字" v-model="keyword" style="margin-bottom: 10px;" @change="loadFlowsWaitingByMe"></el-input>
        </div>
        <div v-if="waitingList.length > 0">
            <van-list v-model="waiting.loading" :finished="waiting.finished" finished-text="" @load="onLoad2">
                <div class="pipeline-user-flow-box" v-for="(userFlow, idx) in waitingList" :key="idx" @click="viewMyApplication(userFlow)">
                    <el-card shadow="always" class="pb-3" :body-style="{ padding: '10px 20px' }">
                        <div style="display: flex;align-items: center;">
                            <img :src="userFlow.avatar" style="border-radius: 50%;width: 40px;height: 40px;">
                            <h3 style="margin: 0 0 0 20px;flex: 4;">
                                <p style="margin: 0;font-weight:400;color:#333333;">@{{ userFlow.user_name }}的@{{ userFlow.flow.name }}</p>
                                <p style="font-size: 13px;color: #ABABAB;margin: 0">类型：@{{ userFlow.flow.name }}</p>
                                <time style="font-size: 13px;color: #ABABAB;">@{{ userFlow.created_at.substring(0, 16) }}</time>
                            </h3>
                        </div>
                    </el-card>
                </div>
            </van-list>
        </div>
        <div v-if="showWaiting" style="display: flex;flex-direction: column;align-items: center;background-color: #fff;margin-top: 70px;">
            <img src="{{asset('assets/img/pipeline/nothing@2x.png')}}" alt="" style="width: 150px;height: 110px;">
            <p style="color: #6F7275;text-align: center;font-size: 14px;font-family:PingFangSC-Regular,PingFang SC;">暂无数据哦~</p>
        </div>
    </div>
</div>
@endsection