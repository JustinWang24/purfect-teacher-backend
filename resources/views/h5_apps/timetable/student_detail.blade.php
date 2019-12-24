@extends('layouts.h5_app')
@section('content')
    <div id="app-init-data-holder"
         data-detailurl="{{ route('h5.timetable.student.detail') }}"
         data-viewurl="{{ route('h5.timetable.student.view') }}"
         data-api="{{ $api_token }}"
         data-type="{{ $type }}"
         data-day="{{ $day }}"
         data-base="{{ url('/') }}"
         data-item="{{ json_encode($timeSlotItem) }}"
         data-course="{{ json_encode($timeSlotItem->course) }}"
         data-materials="{{ json_encode($materials) }}"
         data-teacher="{{ json_encode($timeSlotItem->teacher) }}"
         data-profile="{{ json_encode($timeSlotItem->teacher->profile) }}"
         data-rate="{{ json_encode($rate) }}"
         data-ratesummary="{{ json_encode($rateSummary) }}"
         data-notes="{{ json_encode($notes) }}"
         data-apprequest="1">
    </div>
    <div id="{{ $appName }}" class="school-intro-container">
        <div class="main p-15">
            <p class="text-center">
                <el-button style="color: #0c0c0c;" class="pull-left" @click="back" type="text" size="mini" icon="el-icon-arrow-left"></el-button>
                <span class="text-primary">课程: {{ $timeSlotItem->course->name }}</span>
                <el-button style="color: #0c0c0c;" class="pull-right" @click="showMore" type="text" size="mini" icon="el-icon-more"></el-button>
            </p>
            <el-divider></el-divider>
            <el-row>
                <el-col :span="10">
                    <img class="the-avatar-big" :src="teacherProfile.avatar">
                </el-col>
                <el-col :span="14">
                    <p style="font-size: 13px;">授课老师: <span class="text-primary">@{{ teacher.name }}</span></p>
                    <p style="font-size: 13px;">联系电话: <span class="text-primary">@{{ teacher.mobile }}</span></p>
                </el-col>
            </el-row>
            <el-divider v-if="rateSummary"></el-divider>
            <p v-if="rateSummary" style="text-align: center;">
                综合评价
                <el-rate
                        v-model="rateSummary.average_points"
                        disabled
                        text-color="#ff9900">
                </el-rate>
            </p>
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
            <h5>我的随堂笔记: </h5>
            <div v-for="(n, idx) in notes" :key="idx" class="mb-10">
                <el-card shadow="never">
                    <p style="padding: 0; line-height: 16px;font-size: 12px;"><span class="text-grey"><i class="el-icon-time"></i> @{{ n.created_at }}</span></p>
                    <p style="padding: 0; line-height: 16px;font-size: 12px;">
                        @{{ n.note }}
                    </p>
                </el-card>
            </div>
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
                        <a href="#" @click="writeNote">
                            <i class="el-icon-edit"></i>&nbsp; 随堂笔记
                        </a>
                    </p>
                </li>
            </ul>
        </el-drawer>

        <el-drawer
                size="80%"
                :with-header="false"
                :visible.sync="showTeacherEvaluateTeacherFlag"
                direction="btt">
            <div class="rate-box">
                <h4 class="text-center">@{{ rateTitle }}</h4>

                <p class="demonstration">课前准备情况</p>
                <el-rate class="mt-10" :texts="rateTexts" :disabled="myRate.id" show-text v-model="myRate.prepare"></el-rate>

                <p class="demonstration mt-10">老师课上讲义</p>
                <el-rate class="mt-10" :texts="rateTexts" :disabled="myRate.id" show-text v-model="myRate.material"></el-rate>

                <p class="demonstration mt-10">准时上课</p>
                <el-rate class="mt-10" :texts="rateTexts" :disabled="myRate.id" show-text v-model="myRate.on_time"></el-rate>

                <p class="demonstration mt-10">趣味性</p>
                <el-rate class="mt-10" :texts="rateTexts" :disabled="myRate.id" show-text v-model="myRate.positive"></el-rate>

                <p class="demonstration mt-10">综合结果</p>
                <el-rate class="mt-10" :texts="rateTexts" :disabled="myRate.id" show-text v-model="myRate.result"></el-rate>
                <br>
                <el-input
                        v-if="!myRate.id"
                        style="padding: 10px;"
                        type="textarea"
                        :rows="2"
                        placeholder="选填: 我的留言"
                        v-model="myRate.comment">
                </el-input>
                <p v-if="myRate.id">我的留言: @{{ myRate.comment }}</p>
                <br>
                <el-button class="mt-10" v-if="!myRate.id" type="primary" icon="el-icon-check" @click="submitRate">提交我的评价</el-button>
            </div>
        </el-drawer>

        <el-drawer
                size="80%"
                :visible.sync="showNotesFlag"
                direction="btt">
            <el-row>
                <el-col :span="22">
                    <el-input
                            style="padding: 10px;"
                            type="textarea"
                            :rows="3"
                            placeholder="写点儿什么 ...."
                            v-model="myNote">
                    </el-input>
                    <el-button class="mt-10 pull-right" type="primary" size="mini" icon="el-icon-check" @click="submitNote">保存</el-button>
                </el-col>
            </el-row>

            <div v-for="(n, idx) in notes" :key="idx" style="padding: 10px;">
                <el-card shadow="never">
                    <p style="padding: 0; line-height: 16px;font-size: 12px;"><span class="text-grey"><i class="el-icon-time"></i> @{{ n.created_at }}</span></p>
                    <p style="padding: 0; line-height: 16px;font-size: 12px;">
                         @{{ n.note }}
                    </p>
                </el-card>
            </div>
        </el-drawer>
    </div>
@endsection
