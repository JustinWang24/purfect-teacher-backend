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
        @foreach($list as $item)
        <div class="pipeline-user-flow-box" @click="viewMyApplication({{ $item->userFlow }})">
            <el-card shadow="hover" class="pb-3">
                <div style="display: flex;align-items: center;">
                    <img src="@if(isset($item->user->profile->avatar)){{ $item->user->profile->avatar }}@endif" style="border-radius: 50%;width: 40px;height: 40px;">
                    <h3 style="margin-left: 20px;flex: 4;">
                        <p style="line-height: 0;font-weight:400;color:#333333;">{{ $item->user->name }}的{{ $item->flow->name }}申请</p>
                        <p style="font-size: 13px;color: #ABABAB;margin: 0">类型：{{ $item->flow->name }}</p>
                        <time style="font-size: 13px;color: #ABABAB;">{{ substr($item->created_at, 0, 16) }}</time>
                    </h3>
                </div>
            </el-card>
        </div>
        @endforeach
    </div>
</div>
@endsection