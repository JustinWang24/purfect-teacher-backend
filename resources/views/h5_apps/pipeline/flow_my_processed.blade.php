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
        <div class="pipeline-user-flow-box">
            @foreach($list as $item)
            <el-card shadow="hover" class="pb-3" @click="viewMyApplication({{ $item->userFlow }})">
                <div style="display: flex;align-items: center;">
                    <img src="@if(isset($item->user->profile->avatar)){{ $item->user->profile->avatar }}@endif" width="40">
                    <h3 style="margin-left: 20px;flex: 4;">
                        <p style="line-height: 0;display: flex;justify-content: space-between;">
                            <span>{{ $item->user->name }}的{{ $item->flow->name }}申请</span>
                            <span style="font-weight: 100;font-size: 16px;">
                                @if ($item->done == \App\Utils\Pipeline\IUserFlow::IN_PROGRESS) 进行中 @endif
                                @if ($item->done == \App\Utils\Pipeline\IUserFlow::DONE) 已通过 @endif
                                @if ($item->done == \App\Utils\Pipeline\IUserFlow::TERMINATED) 被拒绝 @endif
                            </span>
                        </p>
                        <p style="font-size: 13px;color: #999;margin: 0">类型：{{ $item->flow->name }}</p>
                        <time style="font-size: 13px;color: #999;">{{ substr($item->created_at, 0, 16) }}</time>
                    </h3>
                </div>
            </el-card>
            @endforeach
        </div>
    </div>
</div>
@endsection