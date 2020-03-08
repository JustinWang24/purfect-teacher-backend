@php
$flowStillInProgress = $startAction->userFlow->done === \App\Utils\Pipeline\IUserFlow::IN_PROGRESS;
@endphp
@extends('layouts.h5_app')
@section('content')
<div id="app-init-data-holder" data-school="{{ $user->getSchoolId() }}" data-useruuid="{{ $user->uuid }}" data-apitoken="{{ $user->api_token }}" data-flowid="{{ $startAction->userFlow->id }}" data-actionid="{{ $userAction ? $userAction->id : null }}" data-theaction="{{ $userAction }}" data-apprequest="1"></div>
<div id="pipeline-flow-view-history-app" class="school-intro-container">
    <div class="main" v-if="isLoading">
        <p class="text-center text-grey">
            <i class="el-icon-loading"></i>&nbsp;数据加载中 ...
        </p>
    </div>
    <div class="main" v-show="!isLoading">
        @if (!$user->isStudent() && $startUser->isStudent())
        <div class="information">
            <h3>基本信息</h3>
            <el-divider></el-divider>
            <h5>
                <p>姓名</p>
                <p>{{ $startUser->name }}</p>
            </h5>
            <el-divider></el-divider>
            <h5>
                <p>性别</p>
                <p>@if($startUser->profile->gender == 1)男@endif
                    @if($startUser->profile->gender == 2)女@endif
                </p>
            </h5>
            <el-divider></el-divider>
            <h5>
                <p>出生年月</p>
                <p>{{ $startUser->profile->birthday }}</p>
            </h5>
            <el-divider></el-divider>
            <h5>
                <p>民族</p>
                <p>{{ $startUser->profile->nation_name }}</p>
            </h5>
            <el-divider></el-divider>
            <h5>
                <p>政治面貌</p>
                <p>{{ $startUser->profile->political_name }}</p>
            </h5>
            <el-divider></el-divider>
            <h5>
                <p>入学时间</p>
                <p>{{ $startUser->profile->year }}</p>
            </h5>
            <el-divider></el-divider>
            <h5>
                <p>身份证号</p>
                <p>{{ $startUser->profile->id_number }}</p>
            </h5>
            <el-divider></el-divider>
            <h5>
                <p>学院</p>
                <p>{{ $startUser->gradeUser->institute->name }}</p>
            </h5>
            <el-divider></el-divider>
            <h5>
                <p>专业</p>
                <p>{{ $startUser->gradeUser->major->name }}</p>
            </h5>
            <el-divider></el-divider>
            <h5>
                <p>班级</p>
                <p>{{ $startUser->gradeUser->grade->name }}</p>
            </h5>
            <el-divider></el-divider>
        </div>
        @endif
        <div class="information">
            <h3>表单信息</h3>
            <el-divider></el-divider>
            @foreach($options as $option)
                <h5>
                    <p>{{ $option['title'] }}</p>
                    <p>{{ $option['value'] }}</p>
                </h5>
                <el-divider></el-divider>
            @endforeach
        </div>
        <div class="information">
            <h3>申请理由</h3>
            <p class="reason">{{ $startAction->content }}</p>
        </div>
        <div class="information">
            {{--<h3>证明材料</h3> 写表单的那个人呢 还没对接过
            <div class="imageBox">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
            </div>--}}
        </div>
        <div class="information">
            <h3>审批人</h3>
            <div class="block">
                <el-timeline>
                    @foreach($handlers as $key => $handler)
                        <el-timeline-item
                            key="{{ $key }}">
                            @foreach($handler as $k => $val)
                                @foreach ($val as $v)
                                    {{ $v->name }}({{ $k }})
                                @endforeach
                            @endforeach
                        </el-timeline-item>
                    @endforeach
                </el-timeline>
            </div>
        </div>
        <div class="information">
            <h3>抄送人</h3>
            <div class="sendBox">
                <figure>
                    @foreach($copys as $copy)
                    <img src="{{asset($copy->user->profile->avatar)}}" width="50" height="50" />
                    <p>{{ $copy->name }}</p>
                    @endforeach;
                </figure>
            </div>
        </div>
    </div>



    <a style="display: block; color: white;text-decoration: none;text-align: center;" href="{{ route('h5.flow.user.in-progress',['api_token'=>$api_token]) }}" class="showMoreButton">返回</a>
</div>
</div>
@endsection
