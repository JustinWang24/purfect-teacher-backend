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
        {{--<div class="information">
            <h3>申请理由</h3>
            <p class="reason">{{ $startAction->content }}</p>
        </div>--}}
        <!-- <div class="information">
            <h3>证明材料</h3> 写表单的那个人呢 还没对接过
            <div class="imageBox">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
                <img src="{{asset('assets/img/bg-02.jpg')}}" alt="" class="image">
            </div>
        </div> -->
        <!-- <div class="information">
            <h3>部门</h3>
            <span class="reason" style="display: inline-block" v-for="(item,index) in activities" :key="index">@{{ item }}</span>
        </div> -->
        <!-- <div class="information">
            <h3>附件</h3>
            <p class="reason" style="color: #0385FF;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;" v-for="(item,index) in activities" :key="index">@{{ item }}</p>
        </div> -->
        <div class="information">
            <h3>
                <span>审批人</span>
                <span style="font-size: 14px; font-weight: 100;">@if (!empty($flowInfo->auto_processed))自动同意@endif</span>
            </h3>
            <div class="block" style="padding: 0 15px;">

                <el-timeline>
                  <el-timeline-item key="0">
                    <el-timeline-item timestamp="{{ substr($startAction->created_at, 0, 16) }}">
                      <img src="{{ $startUser->profile->avatar }}" alt="" style="width: 40px; height: 40px;border-radius: 50%;vertical-align: middle;">
                      {{ $startUser->name }}
                      <span style="text-align: right;"> 发起审批 </span>
                    </el-timeline-item>
                  </el-timeline-item>
                    @foreach($handlers as $key => $handler)
                    <!-- <el-timeline-item key="{{ $key }}" icon="审批状态"  :timestamp="时间戳2018-04-12 20:46">-->
                    <el-timeline-item key="{{ $key+1 }}">
                        @foreach($handler as $k => $val)
                        @foreach ($val as $v)
                        <el-timeline-item @if (!empty($v->result)) result="{{ $v->result->result }}" @if($v->result->result != \App\Utils\Pipeline\IAction::RESULT_PENDING) timestamp="{{ substr($v->result->updated_at, 0, 16) }}" @endif @endif>
                            <img src="{{ $v->profile->avatar }}" alt="" style="width: 40px; height: 40px;border-radius: 50%;vertical-align: middle;">
                            {{ $v->name }}({{ $k }})
                            <span style="text-align: right;">
                              @if (!empty($v->result))
                                @if ($v->result->result == \App\Utils\Pipeline\IAction::RESULT_PENDING) 审批中 @endif
                                @if ($v->result->result == \App\Utils\Pipeline\IAction::RESULT_PASS) 已通过 @endif
                                @if ($v->result->result == \App\Utils\Pipeline\IAction::RESULT_TERMINATE) 被拒绝 @endif
                                @if ($v->result->result == \App\Utils\Pipeline\IAction::RESULT_REJECT) 被驳回 @endif
                              @endif
                            </span>
                        </el-timeline-item>
                        @endforeach
                        @endforeach
                    </el-timeline-item>
                    @endforeach
                </el-timeline>
            </div>
        </div>
        <div class="information">
            <h3>抄送人（多少人）</h3>
            <div class="sendBox">
                @foreach($copys as $copy)
                <figure>
                    <img src="{{asset($copy->user->profile->avatar)}}" width="50" height="50" />
                    <p>{{ $copy->name }}</p>
                </figure>
                @endforeach
            </div>
        </div>
        <!-- <el-button type="primary" style="width: 100%" @click="dialogVisible = true">审批</el-button> -->

        <!-- <el-button type="text" @click="dialogVisible = true">点击打开 Dialog</el-button>
        <el-dialog title="提示" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
            <span>这是一段信息</span>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">取 消</el-button>
                <el-button type="primary" @click="dialogVisible = false">确 定</el-button>
            </span>
        </el-dialog> -->
        @if ($showActionEditForm)
        <a style="display: block; color: white;text-decoration: none;text-align: center;" class="showMoreButton">审批</a>
        @endif
    </div>
</div>
@endsection
