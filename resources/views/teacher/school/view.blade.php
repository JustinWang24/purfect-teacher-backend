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
    <el-carousel arrow="never" type="card" height="300px" v-if="bannerList.length > 0">
        <el-carousel-item v-for="item in bannerList" :key="item.id">
            <img :src="item.image_url" class="banner_img" :οnerrοr="item.image_url" />
        </el-carousel-item>
    </el-carousel>
    <el-carousel arrow="never" type="card" height="300px" v-else v-cloak>
        <el-carousel-item>
            <img src="{{asset('assets/img/slider/fullimage1.jpg')}}" class="banner_img" />
            <img src="{{asset('assets/img/slider/fullimage2.jpg')}}" class="banner_img" />
            <img src="{{asset('assets/img/slider/fullimage3.jpg')}}" class="banner_img" />
        </el-carousel-item>
    </el-carousel>
    <div class="bottom">
        <div class="bottom_first">
            <p class="bottom_title">校园新闻</p>
            <div style="overflow:auto;height: 675px;" v-cloak>
                <div class="bottom_first_content" v-for="item in newsList" :key="item.id" @click="newInfo(item.id)">
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
                <!-- <ul>
                    <li>
                        <img src="" alt="">
                    </li>
                    <li></li>
                    <li></li>
                </ul> -->
                <Calendar @chose-day="clickday" @change-month="changedate"></Calendar>
                <!-- <el-calendar v-model="calendar"></el-calendar> -->
            </div>
            <div class="bottom_second_plan">
                <div class="bottom_title bottom_title_span">校园安排<span @click="drawer = true" style="cursor: pointer">历史安排 ></span></div>
                <p v-for="item in schooleventsList" v-cloak>
                    <span>@{{ item.event_time}}</span>
                    <span>@{{ item.week_idx }}</span>
                    <span>@{{ item.content }}</span>
                </p>
            </div>
            <el-drawer title="校历安排" :before-close="handleClose" :visible.sync="drawer" custom-class="demo-drawer" ref="drawer" v-cloak>
                <div class="demo-drawer__content" v-for="item in schoolalleventsList">
                    <span>@{{ item.event_time}}</span>
                    <span>@{{ item.week_idx }}</span>
                    <span>@{{ item.content }}</span>
                </div>
            </el-drawer>
        </div>
        <div class="bottom_third">
            <p class="bottom_title">值周</p>
            <div style="overflow:auto;height: 675px;" v-cloak ref="scrollTopList">
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
    <el-drawer title="校园新闻详情" :before-close="handleClose" :visible.sync="showNewInfo" custom-class="demo-drawer" v-cloak>
        <div class="demo-drawer__content">
            <p>@{{notice.title}}</p>
            <p>@{{notice.created_at}}</p>
            <div v-for="item in notice.sections" :key="item.id">
                @{{item.content}}
            </div>
            <img :src="notice.image" alt="" v-if="notice.type != 1">
            <!-- <div class="demo-drawer__content_enclosure" v-if="attachments.length">
                <p class="word">附件</p>
                <p class="enclosure">@{{ attachments[0].file_name}}</p>
            </div> -->
        </div>
    </el-drawer>
</div>


<div id="app-init-data-holder" data-school="{{ session('school.id') }}" data-useruuid="{{ \Illuminate\Support\Facades\Auth::user()->uuid }}" data-flowopen="{{ route('teacher.pipeline.flow-open') }}"></div>
@endsection