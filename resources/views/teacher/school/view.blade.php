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
                    我的班级: {{ $gu->grade->name }}
                </h3>
                <p class="text-center">
                    学生: {{ $gu->grade->studentsCount() }},
                    @php
$slot = \App\Utils\Time\GradeAndYearUtil::GetTimeSlot();
                    @endphp
                    目前是: {{ $slot ? $slot->name : '休息时间' }}
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
                        <a href="#">{{ $tc->course->name }}</a>
                    @endforeach
                @endif
                        <a class="text-white" href="{{ route('teacher.elective-course.create',['uuid'=>$teacher->uuid]) }}">申请新开一门选修课</a>
                    </p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="analysis-box m-b-0 bg-blue">
                <h3 class="text-white box-title">Total Course <span class="pull-right"><i
                                class="fa fa-caret-up"></i>765</span></h3>
                <div id="sparkline9"><canvas
                            style="display: inline-block; width: 267px; height: 70px; vertical-align: top;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="analysis-box m-b-0 bg-success">
                <h3 class="text-white box-title">Visitors <span class="pull-right"><i
                                class="fa fa-caret-up"></i> 323</span></h3>
                <div id="sparkline16" class="text-center"><canvas
                            style="display: inline-block; width: 215px; height: 70px; vertical-align: top;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="card card-box">
                <div class="card-head">
                    <header>Income/Expense Report </header>
                    <div class="tools">
                        <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
                        <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
                        <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                    </div>
                </div>
                <div class="card-body no-padding height-9">
                    <div class="row">
                        <canvas id="bar-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="card card-box">
                <div class="card-head">
                    <header>Income/Expense Report</header>
                    <div class="tools">
                        <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
                        <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
                        <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                    </div>
                </div>
                <div class="card-body no-padding height-9">
                    <div class="row">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="profile-sidebar">
                @include('teacher.elements.sidebar.avatar',['profile'=>$teacher->profile])
                @include('teacher.elements.sidebar.about_teacher',['profile'=>$teacher->profile])
            </div>
            <!-- 报名时的表格 -->
            <div class="profile-content">
                <div class="row">
                    <div class="col-12">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
