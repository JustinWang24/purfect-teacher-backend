@php
    use App\Utils\UI\Anchor;
    use App\Utils\UI\Button;
    use App\User;
    use App\Utils\Misc\ConfigurationTool;
    /**
     * @var \App\User $teacher
     */
@endphp
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="analysis-box m-b-0 bg-blue">
                <h3 class="text-white box-title m-4">
                    我的班级
                </h3>
                <p class="text-white">
                    {{ $gradeUser->grade->name }} (学生: {{ $gradeUser->grade->studentsCount() }}人)
                </p>
                <p class="text-white">
                    @php
                        $slot = \App\Utils\Time\GradeAndYearUtil::GetTimeSlot();
                    @endphp
                    目前是: {{ $slot ? $slot->name.'时间' : '休息时间' }}
                </p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="analysis-box m-b-0 bg-danger">
                <h3 class="text-white box-title m-4">
                    我的课程
                </h3>
                <p>
                </p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="analysis-box m-b-0 bg-blue">
                <h3 class="text-white box-title m-4">
                    日常任务
                </h3>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="analysis-box m-b-0 bg-success">
                <h3 class="text-white box-title m-4">
                    消息通知
                </h3>
            </div>
        </div>
    </div>

    <div class="row" id="student-homepage-app">
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card">
                <div class="card-body">
                    @foreach($groupedFlows as $type=>$flows)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h4 class="text-primary">
                                    <b>{{ \App\Models\Pipeline\Flow\Flow::Types()[$type] }}</b>
                                </h4>
                                <el-divider></el-divider>
                                <div class="row">
                                    @foreach($flows as $flow)
                                        <div class="col-4 mb-4 flow-box" v-on:click="startFlow({{ $flow->id }})">
                                            <img src="{{ $flow->getIconUrl() }}" width="50">
                                            <span>{{ $flow->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <p class="pull-left pt-2">我的申请 <i class="el-icon-loading" v-if="isLoading"></i></p>
                        <el-button @click="reloadThisPage" size="medium" type="text" class="pull-right"><i class="el-icon-refresh"></i>&nbsp;刷新</el-button>
                    </header>
                </div>
                <div class="card-body no-padding height-9">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-xl-4" v-for="(userFlow, idx) in flowsStartedByMe" :key="idx">
                            <el-card shadow="hover" class="pb-3">
                                <h3>
                                    <img :src="userFlow.flow.icon" width="40">
                                    <span>@{{ userFlow.flow.name }}</span>
                                </h3>
                                <el-divider></el-divider>
                                <h5 :class="flowResultClass(userFlow.done)">
                                    @{{ flowResultText(userFlow.done) }}
                                </h5>
                                <time class="pull-left" style="font-size: 13px;color: #999;">申请日期: @{{ userFlow.created_at.substring(0, 10) }}</time>
                                <br>
                                <el-divider></el-divider>
                                <div class="clearfix"></div>
                                <el-button @click="viewMyApplication(userFlow)" type="primary" size="mini" class="button pull-left">查看详情</el-button>
                                <el-button v-if="!userFlow.done" @click="cancelMyApplication(userFlow)" type="danger" size="mini" class="button pull-right">撤销</el-button>
                            </el-card>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-useruuid="{{ $student->uuid }}"
         data-flowopen="{{ route('student.pipeline.flow-open') }}"
    ></div>
@endsection
