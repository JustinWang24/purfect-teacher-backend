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

<div id="teacher-homepage-app">
    <el-carousel arrow="never" type="card" height="300px">
        <el-carousel-item v-for="item in bannerList" :key="item.id">
            <img :src="item.image_url" class="banner_img" :οnerrοr="item.image_url" />
        </el-carousel-item>
    </el-carousel>
    <div class="bottom">
        <div class="bottom_first">
            <p class="bottom_title">校园新闻</p>
            <div style="overflow:auto;height: 675px;">
                <div class="bottom_first_content" v-for="item in newsList" :key="item.id">
                    <img v-if="item.image" :src="item.image" alt="" :οnerrοr="item.image_url" class="bottom_first_content_img">
                    <div class="bottom_first_content_right">
                        <p class="bottom_first_content_title">@{{ item.title }}</p>
                        <p class="bottom_first_content_detail">@{{ item.title }}</p>
                        <p class="bottom_first_content_time">@{{ item.created_at }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom_second">
            <div class="bottom_second_calendar">
                <p class="bottom_title">校历</p>
                <!-- <el-calendar v-model="calendar"></el-calendar> -->
            </div>
            <div class="bottom_second_plan">
                <div class="bottom_title bottom_title_span">校园安排<span @click="drawer = true" style="cursor: pointer">历史安排 ></span></div>
                <p v-for="item in schooleventsList">@{{ item.event_time}}&nbsp;&nbsp;&nbsp; @{{ item.week_idx }} &nbsp;&nbsp;&nbsp;@{{ item.content }}</p>
            </div>
            <el-drawer title="校历安排" :before-close="handleClose" :visible.sync="drawer" custom-class="demo-drawer" ref="drawer">
                <div class="demo-drawer__content" v-for="item in schoolalleventsList">
                    @{{ item.event_time}}&nbsp;&nbsp;&nbsp; @{{ item.week_idx }} &nbsp;&nbsp;&nbsp;@{{ item.content }}
                </div>
            </el-drawer>
        </div>
        <div class="bottom_third">
            <p class="bottom_title">值周</p>
            <div style="overflow:auto;height: 675px;" v-infinite-scroll="load">
                <div class="attendance_content" v-for="item in attendanceList" :key="item.id">
                    <div class="attendance_time">@{{ item.start_date }}-@{{ item.end_date }}</div>
                    <div class="attendance_detail">
                        <p><span>负责部门：</span><span>@{{ item.related_organizations[0] }}</span></p>
                        <p><span>值周班级：</span><span>@{{ item.grade_name }}</span></p>
                        <p><span>值周人员：</span><span>@{{ item.high_level }}&nbsp;&nbsp;&nbsp; @{{ item.middle_level }} &nbsp;&nbsp;&nbsp;@{{ item.teacher_level }}</span></p>
                    </div>
                    <p class="attendance_task">@{{item .description }}</p>
                </div>
                <!-- <p v-if="loading">加载中...</p>
                <p v-if="noMore">没有更多了</p> -->
            </div>
        </div>
    </div>
</div>


<div id="app-init-data-holder" data-school="{{ session('school.id') }}" data-useruuid="{{ \Illuminate\Support\Facades\Auth::user()->uuid }}" data-flowopen="{{ route('teacher.pipeline.flow-open') }}"></div>
@endsection