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
                            <el-date-picker
                              v-model="date"
                              type="date"
                              placeholder="选择日期">
                            </el-date-picker>
                            <el-select size="small" v-model="gradeValue" placeholder="请选择班级">
                                <el-option
                                        v-for="item in gradeOptions"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value">
                                </el-option>
                            </el-select>
                            <el-button type="primary" size="small" @click="searchList()">查询</el-button>
                        </div>
                    </header>
                </div>
                <div class="card-body">
                    <el-table
                            v-show="tableData.length > 0"
                            :show-header="false"
                            :data="tableData">
                        <el-table-column
                                prop="slot_name"
                                label="课程">
                        </el-table-column>
                        <el-table-column
                                prop="missing_number"
                                label="旷课">
                            <template slot-scope="scope">
                                <span>旷课  </span>
                                <span class="status_red" v-html="scope.row.missing_number"></span>
                            </template>
                        </el-table-column>
                        <el-table-column
                                prop="actual_number"
                                label="已签到">
                            <template slot-scope="scope">
                                <span>已签到  </span>
                                <span class="status_green" v-html="scope.row.actual_number"></span>
                            </template>
                        </el-table-column>
                        <el-table-column
                                prop="leave_number"
                                label="请假">
                            <template slot-scope="scope">
                                <span>请假  </span>
                                <span class="status_yellow" v-html="scope.row.leave_number"></span>
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
                     <div v-show="tableData.length == 0" class="no-data-img">
                         <img src="{{ asset('assets/img/teacher_blade/no-data.png') }}" alt="">
                         <p>当前列表暂时没有数据哦~</p>
                     </div>
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
                                v-show="detailData.length > 0"
                                :show-header="false"
                                :data="detailData">
                            <el-table-column
                                    prop="name"
                                    label="姓名">
                            </el-table-column>
                            <el-table-column
                                    prop="created_at"
                                    label="日期">
                            </el-table-column>
                            <el-table-column
                                    prop="mold"
                                    label="旷课">
                                <template slot-scope="scope">
                                    <span v-bind:class="{
                                            'status_red': scope.row.mold == 3,
                                            'status_green': scope.row.mold == 1,
                                            'status_yellow': scope.row.mold == 2,}"
                                          v-html="studentsStatus[scope.row.mold]"></span>
                                </template>
                            </el-table-column>
                        </el-table>
                        <div v-show="detailData.length == 0" class="no-data-img">
                            <img src="{{ asset('assets/img/teacher_blade/no-data.png') }}" alt="">
                            <p>当前列表暂时没有数据哦~</p>
                        </div>
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
