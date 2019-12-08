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
        @foreach($gradeUser as $gu)
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="analysis-box m-b-0 bg-blue">
                <h3 class="text-white box-title m-4">
                    我的班级
                </h3>
                <p class="text-white">
                    {{ $gu->grade->name }} (学生: {{ $gu->grade->studentsCount() }}人)
                </p>
                <p class="text-white">
                    @php
$slot = \App\Utils\Time\GradeAndYearUtil::GetTimeSlot();
                    @endphp
                    目前是: {{ $slot ? $slot->name.'时间' : '休息时间' }}
                </p>
            </div>
        </div>
        @endforeach

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="analysis-box m-b-0 bg-danger">
                <h3 class="text-white box-title m-4">
                    我的课程
                </h3>
                @if(count($teacher->myCourses) > 0)
                    <p>
                    @foreach($teacher->myCourses as $tc)
                        @if($tc->course)
                            <a class="text-white" href="#">{{ $tc->course->name }}.</a>
                        @else
                            @php
/* teacher course 记录没有关联任何课程, 说明是脏数据, 需要删除 */
                            $dao = new \App\Dao\Courses\CourseTeacherDao();
                            $dao->delete($tc->id);
                            @endphp
                        @endif
                    @endforeach
                    </p>
                @endif
                    <p>
                        <a class="text-white" href="{{ route('teacher.elective-course.create',['uuid'=>$teacher->uuid]) }}">申请新开一门选修课</a>
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

    <div class="row" id="teacher-homepage-app">
        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
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
        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-head">
                    <header>我发起的流程 <i class="el-icon-loading" v-if="isLoading"></i> </header>
                </div>
                <div class="card-body no-padding height-9">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-xl-4" v-for="(act, idx) in flowsStartedByMe" :key="idx">
                            <el-card shadow="hover" class="pb-3">
                                <h3>
                                    <img :src="act.flow.icon" width="40">
                                    <span>@{{ act.flow.name }}</span>
                                </h3>
                                <el-divider></el-divider>
                                <h4 class="text-info">
                                    @{{ act.name }}
                                </h4>
                                <time class="pull-left" style="font-size: 13px;color: #999;">@{{ act.created_at }}</time>
                                <el-button type="danger" icon="el-icon-delete" size="mini" class="button pull-right">撤销</el-button>
                            </el-card>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-head">
                    <header>等待我审核的流程 <i class="el-icon-loading" v-if="isLoading"></i> </header>
                </div>
                <div class="card-body no-padding height-9">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-xl-4" v-for="(act, idx) in flowsWaitingForMe" :key="idx">
                            <el-card shadow="hover" class="pb-3">
                                <h3>
                                    <img :src="act.flow.icon" width="40">
                                    <span>@{{ act.flow.name }}</span>
                                </h3>
                                <el-divider></el-divider>
                                <h4 class="text-info">
                                    @{{ act.name }}
                                </h4>
                                <time class="pull-left" style="font-size: 13px;color: #999;">@{{ act.created_at }}</time>
                                <el-button type="primary" icon="el-icon-edit" size="mini" class="button pull-right">查看</el-button>
                            </el-card>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-useruuid="{{ \Illuminate\Support\Facades\Auth::user()->uuid }}"
         data-flowopen="{{ route('teacher.pipeline.flow-open') }}"
    ></div>
@endsection
