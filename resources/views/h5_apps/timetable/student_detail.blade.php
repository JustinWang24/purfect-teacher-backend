@extends('layouts.h5_app')
@section('content')
    <div id="app-init-data-holder"
         data-detailurl="{{ route('h5.timetable.student.detail') }}"
         data-viewurl="{{ route('h5.timetable.student.view') }}"
         data-api="{{ $api_token }}"
         data-type="{{ $type }}"
         data-day="{{ $day }}"
         data-item="{{ json_encode($timeSlotItem) }}"
         data-course="{{ json_encode($timeSlotItem->course) }}"
         data-materials="{{ json_encode($materials) }}"
         data-teacher="{{ json_encode($timeSlotItem->teacher) }}"
         data-profile="{{ json_encode($timeSlotItem->teacher->profile) }}"
         data-apprequest="1">
    </div>
    <div id="{{ $appName }}" class="school-intro-container">
        <div class="main p-15">
            <p class="text-center">
                <span class="text-primary">{{ $timeSlotItem->course->name }}</span>
                <el-button style="color: #0c0c0c;" class="pull-right" @click="showMore" type="text" size="mini" icon="el-icon-more"></el-button>
            </p>
            <el-divider></el-divider>
            <el-row>
                <el-col :span="10">
                    <img class="the-avatar-big" :src="teacherProfile.avatar" alt="">
                </el-col>
                <el-col :span="14">
                    <p style="font-size: 13px;">授课老师: <span class="text-primary">@{{ teacher.name }}</span></p>
                    <p style="font-size: 13px;">联系电话: <span class="text-primary">@{{ teacher.mobile }}</span></p>
                </el-col>
            </el-row>
            <el-divider></el-divider>
            <p>
                <i class="el-icon-map-location"></i>&nbsp; 上课地点: <span class="text-primary">@{{ item.building.name }} @{{ item.room.name }}</span>
            </p>
            <p>
                <i class="el-icon-alarm-clock"></i>&nbsp; 上课时间: <span class="text-primary">@{{ item.time_slot.from }} - @{{ item.time_slot.to }}</span>
            </p>
            <el-card class="box-card" shadow="never">
                <p>
                    <i class="el-icon-tickets"></i>&nbsp; 课程提要: <span class="text-grey">无</span>
                </p>
            </el-card>
            <el-card class="box-card mt-10" shadow="never">
                <p>
                    <i class="el-icon-document"></i>&nbsp; 预习材料: <span class="text-grey">无</span>
                </p>
            </el-card>
            <el-card class="box-card mt-10" shadow="never">
                <p>
                    <i class="el-icon-document"></i>&nbsp; 复习材料: <span class="text-grey">无</span>
                </p>
            </el-card>
        </div>
        <el-drawer
                size="30%"
                :with-header="false"
                :visible.sync="showMoreFlag"
                direction="btt">
            <ul class="tools-wrap">
                <li class="tool-item">
                    <p>
                        <a href="#">
                            <i class="el-icon-circle-check"></i>&nbsp; 签到
                        </a>
                    </p>
                </li>
                <li class="tool-item">
                    <p>
                        <a href="#" @click="showEvaluateTeacherForm">
                            <i class="el-icon-star-on"></i>&nbsp; 教师评价
                        </a>
                    </p>
                </li>
                <li class="tool-item">
                    <p>
                        <a href="#">
                            <i class="el-icon-video-pause"></i>&nbsp; 请假
                        </a>
                    </p>
                </li>
                <li class="tool-item">
                    <p>
                        <a href="#">
                            <i class="el-icon-edit"></i>&nbsp; 随堂笔记
                        </a>
                    </p>
                </li>
            </ul>
        </el-drawer>
    </div>
@endsection
