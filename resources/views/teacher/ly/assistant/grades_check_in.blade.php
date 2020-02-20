@extends('layouts.app')
@section('content')
<div id="teacher-assistant-grades-check-in-app">
    <div class="blade_title">班级签到</div>
    <el-row :gutter="20">
        <el-col :span="12">
            <div class="grid-content bg-purple-dark"></div>
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        班级签到
                        <div class="search_filter">
                            <el-select size="small" v-model="filterValue" placeholder="请选择">
                                <el-option
                                        v-for="item in filterOptions"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value">
                                </el-option>
                            </el-select>
                            <el-button type="primary" size="small">查询</el-button>
                        </div>
                    </header>
                </div>
                <div class="card-body">
                    <el-table
                            :show-header="false"
                            :data="tableData">
                        <el-table-column
                                prop="class"
                                label="课程">
                        </el-table-column>
                        <el-table-column
                                prop="stuOff"
                                label="旷课">
                            <template slot-scope="scope">
                                <span>旷课  </span>
                                <span class="status_red" v-html="scope.row.stuOff"></span>
                            </template>
                        </el-table-column>
                        <el-table-column
                                prop="stuNor"
                                label="已签到">
                            <template slot-scope="scope">
                                <span>已签到  </span>
                                <span class="status_green" v-html="scope.row.stuNor"></span>
                            </template>
                        </el-table-column>
                        <el-table-column
                                prop="stuHoli"
                                label="请假">
                            <template slot-scope="scope">
                                <span>请假  </span>
                                <span class="status_yellow" v-html="scope.row.stuHoli"></span>
                            </template>
                        </el-table-column>
                        <el-table-column>
                            <template slot-scope="scope">
                                <span class="showDetail">
                                     <img @click="showDetail(scope.row)"
                                          src="{{ asset('assets/img/teacher_blade/eye.png') }}" class="icon-image">
                                </span>
                            </template>
                        </el-table-column>
                    </el-table>
                </div>
            </div>
        </el-col>
        <el-col :span="12">
            <div class="grid-content bg-purple-dark" v-show="ifShow">
                <div class="card">
                    <div class="card-head">
                        <header class="full-width">
                            签到信息
                        </header>
                    </div>
                    <div class="card-body">
                        <el-table
                                :show-header="false"
                                :data="detailData">
                            <el-table-column
                                    prop="stuName"
                                    label="姓名">
                            </el-table-column>
                            <el-table-column
                                    prop="checkin_date"
                                    label="日期">
                            </el-table-column>
                            <el-table-column
                                    prop="stuStatus"
                                    label="旷课">
                                <template slot-scope="scope">
                                    <span v-bind:class="{
                                            'status_red': scope.row.stuStatus == 3,
                                            'status_green': scope.row.stuStatus == 1,
                                            'status_yellow': scope.row.stuStatus == 2,}"
                                          v-html="studentsStatus[scope.row.stuStatus]"></span>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </div>
            </div>
        </el-col>
    </el-row>
</div>

<div id="app-init-data-holder"
     data-school="{{ session('school.id') }}"
></div>
@endsection
