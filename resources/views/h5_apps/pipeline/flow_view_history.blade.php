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
                <p>{{ substr($startUser->profile->birthday, 0, 10) }}</p>
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
        <!-- <div class="information">
            <h3>申请理由</h3>
            <p class="reason">{{ $startAction->content }}</p>
        </div> -->
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
                        <img src="{{ $startUser->profile->avatar }}" alt="" style="width: 40px; height: 40px;border-radius: 50%;vertical-align: middle;">
                        <div style="flex: 1;margin-left: 20px;">
                            <p style="margin: 0;">{{ $startUser->name }}</p>
                            <p style="margin: 0;">{{ substr($startAction->created_at, 0, 16) }}</p>
                        </div>
                        <span style="text-align: right;font-size: 13px;color: #4FA8FE;"> 发起审批 </span>
                    </el-timeline-item>
                    @foreach($handlers as $key => $handler)
                    <el-timeline-item key="{{ $key+1 }}" @if (!empty($v->result)) result="{{ $v->result->result }}" @if($v->result->result != \App\Utils\Pipeline\IAction::RESULT_PENDING) timestamp="{{ substr($v->result->updated_at, 0, 16) }}" @endif @endif>
                        @foreach($handler as $k => $val)
                        @foreach ($val as $v)
                        <div style="margin-bottom: 10px;display: flex;justify-content: space-between;align-items: center;">
                            <div>
                                <img src="{{ $v->profile->avatar }}" alt="" style="width: 40px; height: 40px;border-radius: 50%;vertical-align: middle;">
                                {{ $v->name }}({{ $k }})
                            </div>
                            <span style="text-align: right;">
                                @if (!empty($v->result))
                                @if ($v->result->result == \App\Utils\Pipeline\IAction::RESULT_PENDING) <span style="color: #FE7B1C;">审批中</span> @endif
                                @if ($v->result->result == \App\Utils\Pipeline\IAction::RESULT_PASS) {{ substr($v->result->updated_at, 5, 11) }} <span style="color: #6DCC58;">已通过</span> @endif
                                @if ($v->result->result == \App\Utils\Pipeline\IAction::RESULT_TERMINATE) {{ substr($v->result->updated_at, 5, 11) }} <span style="color: #FD1B1B;">未通过</span> @endif
                                @if ($v->result->result == \App\Utils\Pipeline\IAction::RESULT_REJECT) {{ substr($v->result->updated_at, 5, 11) }} <span style="color: #FD1B1B;">未通过</span> @endif
                                @endif
                            </span>
                        </div>
                        <!-- 审批意见 if条件-->
                        <!-- <p>审批：</p> -->

                        @endforeach
                        @endforeach
                    </el-timeline-item>
                    @endforeach
                </el-timeline>
            </div>
        </div>
        <div class="information">
            <h3>抄送人（{{ count($copys) }}人）</h3>
            <div class="sendBox">
                @foreach($copys as $copy)
                <figure>
                    <img src="{{asset($copy['avatar'])}}" width="50" height="50" />
                    <p>{{ $copy['name'] }}</p>
                </figure>
                @endforeach
            </div>
        </div>
        @if ($showActionEditForm)
        <div style="display: flex;justify-content: center;background-color: #fff;padding-top: 10px;">
            <el-button type="primary" style="width: 40%;border-radius: 50px;margin-bottom: 20px;" @click="dialogVisible = true">审批</el-button>
        </div>
        @endif
        <el-dialog title="审批" :visible.sync="dialogVisible" width="90%" center>
            <el-input type="textarea" :rows="6" placeholder="请输入审批意见" v-model="textarea" maxlength="100"></el-input>
            <span style="position: relative;top: -18px;left: 85%;">@{{textarea.length}}/100</span>
            <span slot="footer" class="dialog-footer">
                <el-button style="border-radius: 40px;width: 80px;" @click="button(5)">拒 绝</el-button>
                <el-button style="border-radius: 40px;width: 80px;" type="primary" @click="button(3)">同 意</el-button>
            </span>
        </el-dialog>
    </div>
</div>
@endsection