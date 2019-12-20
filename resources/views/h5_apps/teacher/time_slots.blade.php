@extends('layouts.h5_teacher_app')
@section('content')
    <div id="app-init-data-holder"
         data-school="{{ $school->id }}"
    ></div>
    <div id="school-time-slots-teacher-app" class="school-intro-container">
        <div class="main p-15">
            <h2 class="title" style="text-align: center;">
                {{ $season }}
            </h2>
            <el-timeline class="frame-wrap">
                <el-timeline-item
                        v-for="(activity, index) in timeFrame"
                        :key="index"
                        :icon="activity.icon"
                        :type="activity.type"
                        :color="activity.color"
                        :size="activity.size"
                        :timestamp="activity.timestamp">
                    <p style="padding: 0;color: #409EFF;">
                        @{{activity.content}}
                        &nbsp;
                        <i class="el-icon-check" v-if="index === highlightIdx"></i>
                    </p>
                </el-timeline-item>
            </el-timeline>
        </div>
    </div>
@endsection
