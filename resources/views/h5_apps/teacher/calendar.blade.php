@extends('layouts.h5_teacher_app')
@section('content')
    <div id="app-init-data-holder"
         data-school="{{ $school->id }}"
         data-events='{!! $events->toJson() !!}'
         data-tags='{!! json_encode($tags) !!}'
    ></div>
    <div id="school-calendar-teacher-app" class="school-intro-container">
        <div class="main p-15">
            <h2 class="title">
                {{ $pageTitle }}
            </h2>
            <el-timeline style="padding-left: 10px;">
                <el-timeline-item v-for="(ev, idx) in events" :key="idx" :timestamp="ev.event_time" placement="top">
                    <el-card>
                        <h4>@{{ ev.content }}&nbsp;<el-tag size="small">@{{ ev.week }}</el-tag></h4>
                        <p>
                            <el-tag
                                    style="margin-right: 2px;"
                                    v-for="(item, id) in ev.tag"
                                    :key="id"
                                    size="mini">
                                @{{ item }}
                            </el-tag>
                        </p>
                    </el-card>
                </el-timeline-item>
            </el-timeline>
        </div>
    </div>
@endsection
