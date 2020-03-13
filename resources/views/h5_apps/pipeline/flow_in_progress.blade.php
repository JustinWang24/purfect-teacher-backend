@extends('layouts.h5_app')
@section('content')
<div id="app-init-data-holder" data-school="{{ $user->getSchoolId() }}" data-useruuid="{{ $user->uuid }}" data-apitoken="{{ $user->api_token }}" data-apprequest="1"></div>
<div id="student-homepage-app" class="school-intro-container">
    <div class="main" v-if="isLoading">
        <p class="text-center text-grey">
            <i class="el-icon-loading"></i>&nbsp;数据加载中 ...
        </p>
    </div>
    <div class="main p-15">
        <!-- 点击进入详情 -->
        <div class="pipeline-user-flow-box" v-for="(userFlow, idx) in flowsStartedByMe" :key="idx" @click="viewMyApplication(userFlow)">
            <el-card shadow="hover" class="pb-3">
                <div style="display: flex;align-items: center;">
                    <img :src="userFlow.flow.icon" width="40">
                    <h3 style="margin-left: 20px;flex: 4;">
                        <p style="line-height: 0;">@{{ userFlow.user_name }}的@{{ userFlow.flow.name }}</p>
                        <time style="font-size: 13px;color: #999;">@{{ userFlow.created_at.substring(0, 16) }}</time>
                    </h3>
                    <h5 style="flex: 1;">
                        <span :class="flowResultClass(userFlow.done)">@{{ flowResultText(userFlow.done) }}</span>
                    </h5>
                </div>
            </el-card>
        </div>
    </div>
</div>
@endsection