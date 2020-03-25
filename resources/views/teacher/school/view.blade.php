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
            <div>
                <p class="bottom_title"><img src="{{asset('assets/img/teacher_blade/news.png')}}" alt="">&nbsp; 校园新闻</p>
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
            <el-drawer title="校园新闻详情" :before-close="handleClose" :visible.sync="showNewInfo" custom-class="demo-drawer" v-cloak>
                <div class="demo-drawer__content">
                    <p>@{{notice.title}}</p>
                    <p>@{{notice.created_at}}</p>
                    <div v-for="item in notice.sections" :key="item.id" style="display: flex;justify-content: center;">
                        <span v-if="item.media_id === ''" style="width: 95%;display: inline-block;text-indent: 2em;">@{{item.content}}</span>
                        <img v-if="item.media_id" :src="item.content">
                    </div>
                </div>
            </el-drawer>
        </div>
        <div class="bottom_second">
            <div class="bottom_second_calendar">
                <p class="bottom_title"><img src="{{asset('assets/img/teacher_blade/calendar.png')}}" alt="">&nbsp; 校历</p>
                <div style="height: calc(100% - 100px)" v-cloak>
                    <ul>
                        <li @click="prevMonth"><i class="el-icon-arrow-left"></i></li>
                        <li><span style="color: #333;">@{{ yyyy }}年</span><span style="color: #949CA9">@{{mm < 9 ? '0'+(mm+1) : mm+1}}月</span></li>
                        <li @click="nextMonth"><i class="el-icon-arrow-right"></i></li>
                    </ul>
                    <table>
                        <thead>
                            <tr>
                                <td style="color: #333;text-align: center;">周次</td>
                                <td style="color: #949CA9;" v-for="(item,index) in week" :key="index">@{{ item }}</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item,index) in tableDay" :key="index">
                                <th style="width: 75px;">@{{tableWeek[index].name}}</th>
                                <td v-for="(i,index) in item" :key="index" style="width: 45px;">
                                    <span :class="i.name.split('-')[1] == dd ? 'current' : ''">@{{ i.name.split('-')[1] }}</span>
                                    <span v-if="i.events.length > 0" style="display: inline-block;width: 3px;height: 3px;background-color: red;border-radius: 50%;position: relative;top: 10px;left: -15px;"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
            <p class="bottom_title"><img src="{{asset('assets/img/teacher_blade/week.png')}}" alt="">&nbsp; 值周</p>
            <div style="overflow:auto;height: 675px;" v-cloak ref="scrollTopList">
                <div class="attendance_content" v-for="item in attendanceList" :key="item.id">
                    <div class="attendance_time">@{{ item.start_date }}-@{{ item.end_date }}</div>
                    <div class="attendance_detail">
                        <p><span>负责部门：</span><span>@{{ item.related_organizations[0] }}</span></p>
                        <p style="padding: 10px 0;"><span>值周班级：</span><span>@{{ item.grade_name }}</span></p>
                        <p><span>值周人员：</span>
                            <span>
                                <span style="background-color: #E8F4FF;color: #4FA5FE;padding: 1px 5px;">@{{ item.high_level }}</span>&nbsp;&nbsp;&nbsp; 
                                <span style="background-color: #E8F4FF;color: #4FA5FE;padding: 1px 5px;">@{{ item.middle_level }}</span> &nbsp;&nbsp;&nbsp; 
                                <span style="background-color: #E8F4FF;color: #4FA5FE;padding: 1px 5px;">@{{ item.teacher_level }}</span>
                            </span>
                        </p>
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