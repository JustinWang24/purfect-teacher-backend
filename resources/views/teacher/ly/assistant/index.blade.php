@extends('layouts.app')
@section('content')
<div id="teacher-assistant-index-app">
    <div class="banner-list" v-for="(item, i) in bannerData">
        <div class="blade_title" v-html="item.name"></div>
        <div class="card">
            <div class="card-body clearfix">
                <div class="banner-item clearfix" v-for="(item2, i2) in item.helper_page">
                    <dl>
                        <dt><img :src="item2.icon" alt=""></dt>
                        <dd v-html="item2.name"></dd>
                    </dl>
                    <div class="bunner-line" v-if="i2+1 != item.helper_page.length">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--    <div class="blade_title">
            教学助手
        </div>
        <div class="card">
            <div class="card-body clearfix">
                <dl class="banner-item">
                    <dt><img src="{{ asset('assets/img/teacher_blade/blade1.png') }}" alt=""></dt>
                    <dd>课表</dd>
                </dl>
                <div class="bunner-line"></div>
                <dl class="banner-item">
                    <dt><img src="{{ asset('assets/img/teacher_blade/blade2.png') }}" alt=""></dt>
                    <dd>教学资料</dd>
                </dl>
                <div class="bunner-line"></div>
                <dl class="banner-item">
                    <dt><img src="{{ asset('assets/img/teacher_blade/blade3.png') }}" alt=""></dt>
                    <dd>
                        <a href="{{ route('teacher.ly.assistant.check-in') }}">签到</a>
                    </dd>
                </dl>
                <div class="bunner-line"></div>
                <dl class="banner-item">
                    <dt><img src="{{ asset('assets/img/teacher_blade/blade4.png') }}" alt=""></dt>
                    <dd>评分</dd>
                </dl>
                <div class="bunner-line"></div>
                <dl class="banner-item">
                    <dt><img src="{{ asset('assets/img/teacher_blade/blade5.png') }}" alt=""></dt>
                    <dd>选课</dd>
                </dl>
            </div>
        </div>-->
    <!--    <div class="blade_title">
            班主任助手
        </div>
        <div class="card">
            <div class="card-body clearfix">
                <dl class="banner-item">
                    <dt><img src="{{ asset('assets/img/teacher_blade/blade6.png') }}" alt=""></dt>
                    <dd>班级管理</dd>
                </dl>
                <div class="bunner-line"></div>
                <dl class="banner-item">
                    <dt><img src="{{ asset('assets/img/teacher_blade/blade7.png') }}" alt=""></dt>
                    <dd>学生信息</dd>
                </dl>
                <div class="bunner-line"></div>
                <dl class="banner-item">
                    <dt><img src="{{ asset('assets/img/teacher_blade/blade8.png') }}" alt=""></dt>
                    <dd>班级签到</dd>
                </dl>
                <div class="bunner-line"></div>
                <dl class="banner-item">
                    <dt><img src="{{ asset('assets/img/teacher_blade/blade9.png') }}" alt=""></dt>
                    <dd>班级评分</dd>
                </dl>
            </div>
        </div>-->
    <div class="blade_title">
        学生审批
    </div>
    <div class="card bottom-card">
        <div class="card-head">
            <el-tabs
                    style="width: 726px;"
                    :stretch="true"
                    @tab-click="handleClick">
                <el-tab-pane label="待审批" name="first"></el-tab-pane>
                <el-tab-pane label="已审批" name="second"></el-tab-pane>
                <el-tab-pane label="我抄送的" name="third"></el-tab-pane>
            </el-tabs>
        </div>
        <div class="card-body">
            <div class="bottom-search">
                <el-input v-model="input" placeholder="请输入内容"></el-input><el-button type="primary">查询</el-button>
            </div>
            <div class="bottom-table">
                <el-table
                        :show-header="false"
                        :data="tableData"
                        stripe
                        style="width: 100%">
                    <el-table-column
                            prop="state"
                            width="60">
                        <template slot-scope="scope">
                            <img class="blade_listImage" v-if="scope.row.iconState"
                                 src="{{asset('assets/img/teacher_blade/qingjia@2x.png')}}" alt="">
                            <img class="blade_listImage" v-if="!scope.row.iconState"
                                 src="{{asset('assets/img/teacher_blade/tingzhi@2x.png')}}" alt="">
                        </template>
                    </el-table-column>
                    <el-table-column
                            prop="state"
                            label="状态"
                            width="110">
                    </el-table-column>
                    <el-table-column
                            prop="name"
                            label="姓名">
                    </el-table-column>
                    <el-table-column
                            prop="date"
                            label="日期"
                            width="200">
                    </el-table-column>
                    <el-table-column
                            prop="status"
                            width="80px">
                        <template slot-scope="scope">
                            <span
                                    v-bind:class="{
                                'status_red': scope.row.status == 0,
                                'status_green': scope.row.status == 1,
                                'status_yellow': scope.row.status == 2,
                                'status_gray': scope.row.status == 3,
                                'status_black': scope.row.status == 4
                            }">
                            @{{statusMap[scope.row.status]}}
                            </span>
                        </template>
                    </el-table-column>
                    <el-table-column
                            width="80px">
                        <img src="{{ asset('assets/img/teacher_blade/eye.png') }}" class="icon-image">
                    </el-table-column>
                </el-table>
            </div>
        </div>
    </div>
</div>


<div id="app-init-data-holder"
     data-school="{{ session('school.id') }}"
></div>

@endsection
