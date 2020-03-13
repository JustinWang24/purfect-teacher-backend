@extends('layouts.h5_app')
@section('content')
<div id="app-init-data-holder" data-school="{{ $user->getSchoolId() }}" data-useruuid="{{ $user->uuid }}" data-apitoken="{{ $user->api_token }}" data-apprequest="1"></div>
<div id="student-list-app" class="school-intro-container">
    <div class="main" v-if="isLoading">
        <p class="text-center text-grey">
            <i class="el-icon-loading"></i>&nbsp;数据加载中 ...
        </p>
    </div>
    <div class="main p-15">
        <div class="pipeline-user-flow-box">
            <!-- foreach -->
          @foreach($list as $item)
            <el-card shadow="hover" class="pb-3" click="viewMyApplication({{ $item->userFlow }})">
                <div style="display: flex;align-items: center;">
                    <img src="@if(isset($item->userFlow->user->profile->avatar)){{ $item->userFlow->user->profile->avatar }}@endif" width="40">
                    <h3 style="margin-left: 20px;flex: 4;">
                        <p style="line-height: 0;">{{ $item->userFlow->user->name }}的{{ $item->flow->name }}申请</p>
                        <p style="font-size: 13px;color: #999;margin: 0">类型：{{ $item->flow->name }}</p>
                        <time style="font-size: 13px;color: #999;">{{ substr($item->userFlow->created_at, 0, 16) }}</time>
                    </h3>
                </div>
            </el-card>
            @endforeach
        </div>
    </div>
</div>
@endsection
