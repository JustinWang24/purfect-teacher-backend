@extends('layouts.h5_app')
@section('content')
    <div id="app-init-data-holder"
         data-detailurl="{{ route('h5.timetable.student.detail') }}"
         data-viewurl="{{ route('h5.timetable.student.view') }}"
         data-api="{{ $api_token }}"
         data-type="{{ $type }}"
         data-day="{{ $day }}"
         data-today="{{ json_encode($today) }}"
         data-apprequest="1">
    </div>
    <div id="{{ $appName }}" class="school-intro-container">
        <div class="main p-15">
            <p class="text-center">
                <el-button class="pull-left" @click="move(true)" size="mini" type="primary" icon="el-icon-arrow-left">前一天</el-button>
                <span>@{{ today.current.date }}(@{{ today.current.calendarWeekIndex }})</span>
                <el-button class="pull-right" @click="move(false)" size="mini" type="primary">后一天<i class="el-icon-arrow-right el-icon--right"></i></el-button>
            </p>
            <el-card class="box-card mb-10" v-for="(timeSlot, idx) in list" :key="idx">
                <div slot="header" class="clearfix">
                    <i v-if="timeSlot.current" class="el-icon-alarm-clock"></i>
                    <i v-if="!timeSlot.current" class="el-icon-timer"></i>
                    <span>
                        @{{ timeSlot.from }} - @{{ timeSlot.to }}
                    </span>&nbsp;
                    <span class="text-grey">@{{  idx < currentTimeSlotIndex &&  timeSlot.lesson !== '' ? '已结束' : '' }}</span>
                    <el-tag
                            v-if="timeSlot.current"
                            size="mini"
                            effect="dark">
                        进行中
                    </el-tag>
                    <el-button v-if="timeSlot.lesson !== ''" style="float: right; padding: 3px 0" type="text" @click="enter(timeSlot)">进入课堂</el-button>
                </div>
                <div v-if="timeSlot.lesson === ''" class="text item">
                    <p class="text-grey">暂无安排, 自由活动时间</p>
                </div>
                <div v-if="timeSlot.lesson !== ''" class="text item">
                    <p>
                        <span>课程</span>: <span class="text-primary">@{{ timeSlot.lesson.course }}</span>
                    </p>
                    <p>
                        <span>上课地点</span>: <span class="text-primary">@{{ timeSlot.lesson.building }} @{{ timeSlot.lesson.room }}</span>
                    </p>
                    <p>
                        <span>老师</span>: <span class="text-primary">@{{ timeSlot.lesson.teacher }}</span>
                    </p>
                </div>
            </el-card>
        </div>
    </div>
@endsection
