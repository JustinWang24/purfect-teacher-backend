@php
$flowStillInProgress = $userFlow->done === \App\Utils\Pipeline\IUserFlow::IN_PROGRESS;
@endphp
@extends('layouts.h5_app')
@section('content')
<div id="app-init-data-holder" data-school="{{ $user->getSchoolId() }}" data-useruuid="{{ $user->uuid }}" data-apitoken="{{ $user->api_token }}" data-flowid="{{ $userFlow->id }}" data-actionid="{{ $action ? $action->id : null }}" data-theaction="{{ $action }}" data-apprequest="1"></div>
<div id="pipeline-flow-view-history-app" class="school-intro-container">
    <div class="main" v-if="isLoading">
        <p class="text-center text-grey">
            <i class="el-icon-loading"></i>&nbsp;数据加载中 ...
        </p>
    </div>
    <div class="main" v-show="!isLoading">
        <div class="information">
            <h3>基本信息</h3>
            <el-divider></el-divider>
            <h5>
                <p>姓名</p>
                <p>小明</p>
            </h5>
            <el-divider></el-divider>
        </div>
        <div class="information">
            <h3>家庭情况</h3>
            <el-divider></el-divider>
            <h5>
                <p>家庭住址：</p>
                <p>哈哈哈哈哈哈哈哈哈哈或或或或或或或或或或或或或或或或或或或或或或或或或或或或</p>
            </h5>
            <el-divider></el-divider>
        </div>
        <div class="information">
            <h3>申请理由</h3>
            <p class="reason">哈哈哈哈哈哈哈哈哈哈或或或或或或或或或或或或或或或或或或或或或哈哈哈哈哈哈哈哈哈哈或或或或或或或或或或或或或或或或或或或或或或或哈哈哈哈哈哈哈哈哈哈或或或或或或或或或或或或或</p>
        </div>
        <div class="information">
            <h3>证明材料</h3>
            <div class="imageBox">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
            </div>
        </div>
        <div class="information">
            <h3>审批人</h3>
            <div class="block">
                <el-timeline>
                    <el-timeline-item v-for="(activity, index) in activities" :key="index" :icon="activity.icon" :type="activity.type" :color="activity.color" :size="activity.size" :timestamp="activity.timestamp">
                        @{{activity.content}}
                    </el-timeline-item>
                </el-timeline>
            </div>
        </div>
        <div class="information">
            <h3>抄送人</h3>
            <div class="sendBox">
                <figure>
                    <img src="{{asset('assets/img/dp.jpg')}}" width="50" height="50" />
                    <p>谁谁谁</p>
                </figure>
            </div>
        </div>
    </div>



    <a style="display: block; color: white;text-decoration: none;text-align: center;" href="{{ route('h5.flow.user.in-progress',['api_token'=>$api_token]) }}" class="showMoreButton">返回</a>
</div>
</div>
@endsection